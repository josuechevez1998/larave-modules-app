import './bootstrap';
import { initFlowbite } from 'flowbite';

document.addEventListener('DOMContentLoaded', () => {
    initFlowbite();
});

document.addEventListener('livewire:navigated', () => {
    initFlowbite();
});
