import {startStimulusApp} from '@symfony/stimulus-bundle';
import CharacterCounter from 'stimulus-character-counter'
import Popover from 'stimulus-popover'

const app = startStimulusApp();
app.register('character-counter', CharacterCounter)
app.register('popover', Popover)
