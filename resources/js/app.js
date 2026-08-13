document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');

    if (!toggle || !menu) {
        return;
    }

    const openIcon = toggle.querySelector('.icon-open');
    const closeIcon = toggle.querySelector('.icon-close');

    toggle.addEventListener('click', () => {
        const isOpen = !menu.classList.contains('hidden');

        menu.classList.toggle('hidden');
        openIcon?.classList.toggle('hidden');
        closeIcon?.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', String(!isOpen));
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const closeAllLanguageSwitches = () => {
        document.querySelectorAll('[data-language-switch]').forEach((root) => {
            const menu = root.querySelector('[data-language-switch-menu]');
            const toggle = root.querySelector('[data-language-switch-toggle]');

            if (menu) {
                menu.classList.add('hidden');
            }
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    };

    document.querySelectorAll('[data-language-switch]').forEach((root) => {
        const toggle = root.querySelector('[data-language-switch-toggle]');
        const menu = root.querySelector('[data-language-switch-menu]');

        if (!toggle || !menu) {
            return;
        }

        toggle.addEventListener('click', (event) => {
            event.stopPropagation();

            const isOpen = !menu.classList.contains('hidden');

            closeAllLanguageSwitches();

            if (!isOpen) {
                menu.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-language-switch]')) {
            closeAllLanguageSwitches();
        }
    });
});
