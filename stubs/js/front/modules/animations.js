const ANIMATION_CLASSES = [
    'fade-in-up',
    'fade-in-left',
    'fade-in-right',
]

function throttle(callback, limit = 100) {
    let waiting = false

    return (...args) => {
        if (waiting) {
            return
        }

        callback(...args)

        waiting = true

        setTimeout(() => {
            waiting = false
        }, limit)
    }
}

function debugAnimations(...args) {
    if (window.frontDebugAnimations === true || localStorage.getItem('frontDebugAnimations') === '1') {
        console.log('[front animations]', ...args)
    }
}

export function createAnimations() {
    const observers = new Map()
    let initialized = false

    function getAnimatedElements(root = document) {
        return root.querySelectorAll(
            ANIMATION_CLASSES.map(className => `.${className}`).join(', ')
        )
    }

    function normalizeElements(elements) {
        if (!elements) {
            return []
        }

        if (elements instanceof Element) {
            return [elements]
        }

        return [...elements]
    }

    return {
        init(root = document) {
            if (initialized) {
                debugAnimations('already initialized')
                return
            }

            initialized = true

            const elements = getAnimatedElements(root)

            debugAnimations('init', {
                count: elements.length,
                elements: [...elements],
            })

            this.observe(elements)
            this.initParallax(root)
        },

        observe(elements, options = {}) {
            const items = normalizeElements(elements).filter((element) => {
                return !element.classList.contains('visible')
                    && element.dataset.animationObserved !== 'true'
            })

            debugAnimations('observe', {
                count: items.length,
                items,
                options,
            })

            if (!items.length) {
                return null
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    debugAnimations('intersection', {
                        element: entry.target,
                        isIntersecting: entry.isIntersecting,
                        ratio: entry.intersectionRatio,
                    })

                    if (!entry.isIntersecting) {
                        return
                    }

                    const element = entry.target

                    if (element.dataset.animationProcessing === 'true') {
                        debugAnimations('skip already processing', element)
                        return
                    }

                    element.dataset.animationProcessing = 'true'

                    const delay = window.__previewMode
                        ? 0
                        : Number(element.dataset.animationDelay ?? options.delay ?? 0)

                    debugAnimations('animate', {
                        element,
                        delay,
                        classes: [...element.classList],
                    })

                    setTimeout(() => {
                        element.classList.add('visible')

                        delete element.dataset.animationProcessing

                        debugAnimations('visible added', element)
                    }, delay)

                    observer.unobserve(element)
                })
            }, {
                threshold: options.threshold ?? 0.1,
                rootMargin: options.rootMargin ?? '0px 0px -50px 0px',
            })

            items.forEach((element) => {
                element.dataset.animationObserved = 'true'
                observer.observe(element)
            })

            const id = `observer-${observers.size + 1}`

            observers.set(id, observer)

            return id
        },

        observeNew(root = document) {
            const elements = [...getAnimatedElements(root)].filter((element) => {
                return !element.classList.contains('visible')
                    && element.dataset.animationObserved !== 'true'
            })

            debugAnimations('observeNew', {
                count: elements.length,
                elements,
            })

            return this.observe(elements)
        },

        play(selectorOrElement, animationClass = 'fade-in-up', delay = 0) {
            const elements = typeof selectorOrElement === 'string'
                ? document.querySelectorAll(selectorOrElement)
                : normalizeElements(selectorOrElement)

            normalizeElements(elements).forEach((element, index) => {
                element.classList.add(animationClass)

                setTimeout(() => {
                    element.classList.add('visible')
                }, delay + index * 100)
            })
        },

        initParallax(root = document) {
            const elements = root.querySelectorAll('[data-parallax]')

            if (!elements.length) {
                return
            }

            const handleScroll = throttle(() => {
                const scrolled = window.pageYOffset

                elements.forEach((element) => {
                    const speed = parseFloat(element.dataset.parallax) || 0.5
                    const yPos = -(scrolled * speed)

                    element.style.transform = `translateY(${yPos}px)`
                })
            }, 16)

            window.addEventListener('scroll', handleScroll, { passive: true })

            observers.set('parallax', {
                disconnect() {
                    window.removeEventListener('scroll', handleScroll)
                },
            })
        },

        destroy() {
            observers.forEach(observer => observer.disconnect())
            observers.clear()
            initialized = false
        },
    }
}