import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        postId: Number,
    }

    reactionTypes = {
        'like': '&#128077',
        'dislike': '&#128078',
        'love': '&#128150',
        'laugh': '&#128514',
        'sad': '&#128549',
    }

    setReaction(event) {
        const defaultReaction = document.querySelector(`#defaultReaction-${this.postIdValue}`)
        const defaultReactionType = document.querySelector(`#defaultReactionType-${this.postIdValue}`)
        const userReaction = event.target.getAttribute('data-type')

        fetch(`/reaction/posts/${this.postIdValue}`, {
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
}
