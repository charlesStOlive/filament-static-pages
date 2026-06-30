import { createAnimations } from './modules/animations'
import { createFrontApp } from './core/FrontApp'
import { registerCoreComponents } from './alpine/registerCoreComponents'

const services = {
    animations: createAnimations(),
}

function debug(...args) {
    if (localStorage.getItem('frontDebug') === '1') {
        console.log('[front]', ...args)
    }
}

function registerFrontAlpineComponents(Alpine) {
    debug('registerFrontAlpineComponents', Alpine)

    Alpine.data('frontApp', () => createFrontApp(services))

    registerCoreComponents(Alpine)
}

document.addEventListener('alpine:init', () => {
    debug('alpine:init received', {
        hasAlpine: !!window.Alpine,
        hasLivewire: !!window.Livewire,
        previewMode: window.__previewMode === true,
    })

    if (!window.Alpine) {
        console.warn('[front] alpine:init reçu mais window.Alpine est introuvable')
        return
    }

    registerFrontAlpineComponents(window.Alpine)
})

document.addEventListener('livewire:init', () => {
    debug('livewire:init received', {
        hasLivewire: !!window.Livewire,
    })

    if (!window.Livewire) {
        return
    }

    window.Livewire.hook('commit', ({ succeed }) => {
        succeed(() => {
            queueMicrotask(() => {
                debug('livewire commit succeeded: observe new animated elements')

                services.animations.observeNew(document)
            })
        })
    })
})

// Debug utile si Alpine n’est jamais chargé.
window.addEventListener('DOMContentLoaded', () => {
    debug('DOMContentLoaded', {
        hasAlpine: !!window.Alpine,
        hasLivewire: !!window.Livewire,
        previewMode: window.__previewMode === true,
    })

    if (!window.Alpine) {
        console.warn(
            '[front] Alpine absent. Sur cette page, il faut charger les scripts Livewire ou installer Alpine dans le bundle front.'
        )
    }
})