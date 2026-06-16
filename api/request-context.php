<?php
if (!function_exists('dbbet_server_value')) {
    function dbbet_server_value(string $key): string {
        return isset($_SERVER[$key]) ? trim((string) $_SERVER[$key]) : '';
    }
}

if (!function_exists('dbbet_forwarded_value')) {
    function dbbet_forwarded_value(string $key): string {
        $value = dbbet_server_value($key);
        if ($value === '') {
            return '';
        }
        $parts = explode(',', $value);
        return trim((string) ($parts[0] ?? ''));
    }
}

if (!function_exists('dbbet_parse_host_candidate')) {
    function dbbet_parse_host_candidate(string $candidate): array {
        $candidate = trim($candidate);
        if ($candidate === '') {
            return ['', null];
        }

        $candidate = preg_replace('/[\r\n\s]+/u', '', $candidate) ?? '';
        if ($candidate === '') {
            return ['', null];
        }

        if (strpos($candidate, ',') !== false) {
            $candidate = trim((string) explode(',', $candidate)[0]);
        }

        if (!preg_match('~^[a-z][a-z0-9+.-]*://~i', $candidate)) {
            $candidate = 'http://' . $candidate;
        }

        $parts = @parse_url($candidate);
        if (!is_array($parts)) {
            return ['', null];
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        $port = $parts['port'] ?? null;
        if ($host === '') {
            return ['', null];
        }

        $isValidHost = $host === 'localhost'
            || filter_var($host, FILTER_VALIDATE_IP)
            || (bool) preg_match('/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)*[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/i', $host);

        if (!$isValidHost) {
            return ['', null];
        }

        if ($port !== null) {
            $port = (int) $port;
            if ($port < 1 || $port > 65535) {
                $port = null;
            }
        }

        return [$host, $port];
    }
}

if (!function_exists('dbbet_detect_host')) {
    function dbbet_detect_host(): array {
        $candidates = [
            dbbet_server_value('HTTP_HOST'),
            dbbet_server_value('SERVER_NAME'),
        ];

        foreach ($candidates as $candidate) {
            [$host, $port] = dbbet_parse_host_candidate($candidate);
            if ($host !== '') {
                return [$host, $port];
            }
        }

        return ['localhost', null];
    }
}

if (!function_exists('dbbet_is_local_host')) {
    function dbbet_is_local_host(string $host): bool {
        return in_array($host, ['localhost', '127.0.0.1', '::1'], true);
    }
}

if (!function_exists('dbbet_detect_scheme')) {
    function dbbet_detect_scheme(): string {
        $forwardedProto = strtolower(dbbet_forwarded_value('HTTP_X_FORWARDED_PROTO'));
        if (in_array($forwardedProto, ['http', 'https'], true)) {
            return $forwardedProto;
        }

        $forwardedSsl = strtolower(dbbet_server_value('HTTP_X_FORWARDED_SSL'));
        if ($forwardedSsl === 'on') {
            return 'https';
        }

        $https = strtolower(dbbet_server_value('HTTPS'));
        if ($https !== '' && $https !== 'off' && $https !== '0') {
            return 'https';
        }

        $requestScheme = strtolower(dbbet_server_value('REQUEST_SCHEME'));
        if (in_array($requestScheme, ['http', 'https'], true)) {
            return $requestScheme;
        }

        $serverPort = dbbet_server_value('SERVER_PORT');
        if ($serverPort === '443') {
            return 'https';
        }

        return 'http';
    }
}

if (!function_exists('dbbet_canonical_scheme')) {
    function dbbet_canonical_scheme(): string {
        return dbbet_detect_scheme();
    }
}

if (!function_exists('dbbet_canonical_authority')) {
    function dbbet_canonical_authority(): string {
        [$host, $port] = dbbet_detect_host();
        $host = preg_replace('/^www\./i', '', $host) ?? $host;
        $scheme = dbbet_canonical_scheme();
        $defaultPort = $scheme === 'https' ? 443 : 80;

        if ($port !== null && $port !== $defaultPort && dbbet_is_local_host($host)) {
            return $host . ':' . $port;
        }

        return $host;
    }
}

if (!function_exists('dbbet_request_authority')) {
    function dbbet_request_authority(): string {
        return dbbet_canonical_authority();
    }
}

if (!function_exists('dbbet_request_base_url')) {
    function dbbet_request_base_url(): string {
        return dbbet_canonical_scheme() . '://' . dbbet_canonical_authority() . '/';
    }
}
