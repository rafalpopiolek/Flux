import {startStimulusApp} from '@symfony/stimulus-bundle';
import CharacterCounter from 'stimulus-character-counter'

const app = startStimulusApp();
app.register('character-counter', CharacterCounter)