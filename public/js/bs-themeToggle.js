function getTheme() {
    return localStorage.getItem('theme') || (window.matchMedia(('prefers-color-scheme: dark')).matches ? 'dark' : 'light');
}
document.getElementById('themeToggle').addEventListener('click', () => {
    const currentTheme = getTheme();
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
});
document.documentElement.setAttribute('data-bs-theme', getTheme());