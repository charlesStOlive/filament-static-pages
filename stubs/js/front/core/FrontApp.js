export function createFrontApp({ animations }) {
    return {
        init() {
            animations.init()
        },

        triggerAnimation(selector, animationType = 'fade-in-up', delay = 0) {
            animations.play(selector, animationType, delay)
        },

        scrollTo(elementId) {
            const element = document.getElementById(elementId)

            if (!element) {
                return
            }

            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            })
        },
    }
}