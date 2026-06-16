!(function () {
    const e = document.querySelectorAll(".js-menu-btn");
    function t() {
        const e = this.getAttribute("aria-expanded"),
            t = this.querySelector(".js-faq-content"),
            c = t.scrollHeight;
        "false" == e ? (this.setAttribute("aria-expanded", "true"), (t.style.height = `${c}px`)) : (this.setAttribute("aria-expanded", "false"), (t.style.height = 0));
    }
    e &&
        e.forEach((e) => {
            e.addEventListener("click", () => {
                e.nextElementSibling.classList.toggle("active"), e.classList.toggle("active");
            });
        }),
        document.querySelectorAll(".js-faq-item").forEach((e) => e.addEventListener("click", t));
    const c = document.querySelector(".burger");
    c &&
        c.addEventListener("click", () => {
            document.querySelector(".js-menu").classList.toggle("active"), c.classList.toggle("active");
        });
    const n = document.querySelector(".js-copy");
    const o = document.querySelector(".js-copy-text");
    const r = (e) => {
        const t = document.createElement("textarea");
        t.value = e, t.setAttribute("readonly", ""), t.style.position = "absolute", t.style.left = "-9999px", document.body.appendChild(t), t.select();
        try {
            document.execCommand("copy");
        } finally {
            document.body.removeChild(t);
        }
    };
    n &&
        o &&
        n.addEventListener("click", (e) => {
            e.preventDefault();
            const t = o.textContent.trim(),
                c = n.dataset.copyDefault || n.textContent.trim(),
                a = n.dataset.copySuccess || "Copied!";
            const i = () => {
                n.textContent = a;
                n.classList.add('is-copied');
                clearTimeout(n._copyTimeout), (n._copyTimeout = setTimeout(() => {
                    n.textContent = c;
                    n.classList.remove('is-copied');
                }, 1800));
            };
            navigator.clipboard && window.isSecureContext
                ? navigator.clipboard
                      .writeText(t)
                      .then(i)
                      .catch(() => {
                          try {
                              r(t), i();
                          } catch (e) {
                              console.error("Failed to copy: ", e);
                          }
                      })
                : (() => {
                      try {
                          r(t), i();
                      } catch (e) {
                          console.error("Failed to copy: ", e);
                      }
                  })();
        });
})();


(function () {
    const initMobileBannerAutoplay = () => {
        const video = document.querySelector('.banner-1-video');
        if (!video) return;

        video.muted = true;
        video.defaultMuted = true;
        video.playsInline = true;
        video.preload = 'auto';
        video.setAttribute('muted', '');
        video.setAttribute('autoplay', '');
        video.setAttribute('playsinline', '');
        video.setAttribute('webkit-playsinline', '');
        video.setAttribute('poster', 'assets/img/banner-1-custom.jpg');

        const tryPlay = () => {
            const playPromise = video.play();
            if (playPromise && typeof playPromise.catch === 'function') {
                playPromise.catch(() => {});
            }
        };

        const refreshVideo = () => {
            try {
                video.load();
            } catch (e) {}
        };

        refreshVideo();

        if (document.visibilityState === 'visible') {
            tryPlay();
        }

        ['loadedmetadata', 'loadeddata', 'canplay', 'canplaythrough'].forEach((eventName) => {
            video.addEventListener(eventName, tryPlay, { passive: true });
        });

        video.addEventListener('error', refreshVideo, { passive: true });

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') tryPlay();
        });

        ['touchstart', 'pointerdown', 'click'].forEach((eventName) => {
            document.addEventListener(eventName, tryPlay, { passive: true, once: true });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileBannerAutoplay, { once: true });
    } else {
        initMobileBannerAutoplay();
    }
})();


(function () {
    const initBannerFallback = () => {
        const link = document.querySelector('.banner-1-link');
        const video = document.querySelector('.banner-1-video');
        const fallback = document.querySelector('.banner-1-fallback');
        if (!link || !video || !fallback) return;

        const showVideo = () => link.classList.add('is-video-ready');
        const showFallback = () => link.classList.remove('is-video-ready');

        ['playing', 'play', 'canplay', 'canplaythrough', 'loadeddata'].forEach((eventName) => {
            video.addEventListener(eventName, showVideo, { passive: true });
        });

        ['error', 'stalled', 'abort'].forEach((eventName) => {
            video.addEventListener(eventName, showFallback, { passive: true });
        });

        setTimeout(() => {
            if (!video.currentTime || video.paused) {
                showFallback();
            }
        }, 1200);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBannerFallback, { once: true });
    } else {
        initBannerFallback();
    }
})();
