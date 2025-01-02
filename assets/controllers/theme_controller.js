import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const htmlElement = document.querySelector("html")
        const theme = localStorage.getItem('theme')

        if (theme === 'dark') {
            htmlElement.classList.add('dark')
            return
        }

        htmlElement.classList.remove('dark')
    }

    toggle() {
        const htmlElement = document.querySelector("html")
        const isDarkTheme = htmlElement.classList.contains('dark')

        if (isDarkTheme) {
            htmlElement.classList.remove('dark')
            localStorage.setItem('theme', 'light')
            return
        }

        htmlElement.classList.add('dark')
        localStorage.setItem('theme', 'dark')
    }
}
