import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        id: Number,
        target: String,
    }

    reactionTypes = {
        'default': '&#x1F44D;&#x1F3FB;',
        'like': '&#128077;',
        'dislike': '&#128078;',
        'love': '&#128150;',
        'laugh': '&#128514;',
        'sad': '&#128549;',
    }

    setReaction(event) {
        const defaultReaction = document.querySelector(`#defaultReaction-${this.idValue}`)
        const defaultReactionType = document.querySelector(`#defaultReactionType-${this.idValue}`)
        const userReaction = event.target.getAttribute('data-type')

        fetch(`/reaction/${this.targetValue}/${this.idValue}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                type: userReaction
            }),
        })

        defaultReaction.innerHTML = this.reactionTypes[userReaction]
        defaultReactionType.innerHTML = userReaction
    }

    removeReaction() {
        const defaultReactionContainer = document.querySelector(`#defaultReactionContainer-${this.idValue}`)
        const defaultReaction = document.querySelector(`#defaultReaction-${this.idValue}`)
        const defaultReactionType = document.querySelector(`#defaultReactionType-${this.idValue}`)
        const userReaction = defaultReactionContainer.getAttribute('data-type')

        fetch(`/reaction/remove/${this.targetValue}/${this.idValue}`, {
            method: 'POST',
        })

        defaultReaction.innerHTML = this.reactionTypes[userReaction]
        defaultReactionType.innerHTML = 'like'
    }
}
