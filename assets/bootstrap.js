import {startStimulusApp} from '@symfony/stimulus-bundle';
import CharacterCounter from 'stimulus-character-counter'
import Clipboard from 'stimulus-clipboard'

const app = startStimulusApp();
app.register('character-counter', CharacterCounter)
app.register('clipboard', Clipboard)