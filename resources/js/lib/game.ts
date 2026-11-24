import type { GameImage } from '@/types';

export function shuffle<T>(items: readonly T[]): T[] {
    const shuffled = [...items];

    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }

    return shuffled;
}

export function imageMap(images: GameImage[]): Map<number, GameImage> {
    return new Map(images.map((image) => [image.id, image]));
}

/**
 * Shared SortableJS options for the tray and the slots. Pointer-based fallback dragging behaves the same
 * with mouse, pen and touch; on touch a short press starts the drag so a swipe still scrolls the tray.
 */
export const dragOptions = {
    group: 'symphony',
    animation: 150,
    draggable: '.tile',
    filter: '.zoom-button, .remove-button',
    preventOnFilter: false,
    forceFallback: true,
    fallbackOnBody: true,
    fallbackTolerance: 6,
    delay: 140,
    delayOnTouchOnly: true,
    touchStartThreshold: 6,
    ghostClass: 'tile-ghost',
    chosenClass: 'tile-chosen',
    dragClass: 'tile-drag',
    // Vertical page scrolling while dragging is handled by useStickyEdgeScroll (the header is sticky).
    scroll: false,
    emptyInsertThreshold: 12,
} as const;

export const sectionToneClasses: Record<string, string> = {
    unmet: 'bg-tone-unmet',
    rtb: 'bg-tone-rtb',
    ctrl: 'bg-tone-ctrl',
    rct: 'bg-tone-rct',
    rwe: 'bg-tone-rwe',
    add: 'bg-tone-add',
};

export const colourKey = [
    { tone: 'unmet', label: 'Unmet need' },
    { tone: 'rtb', label: 'Reason to believe' },
    { tone: 'ctrl', label: 'Control that’s fast and shown to last' },
    { tone: 'rct', label: 'RCT' },
    { tone: 'rwe', label: 'RWE' },
    { tone: 'add', label: 'Additional data' },
] as const;

export function verdictFor(score: number, total: number, name: string): string {
    if (score === total) {
        return `Perfect harmony, ${name}. Every image is in its place.`;
    }

    if (score >= total * 0.72) {
        return `Nearly there, ${name}. A few notes are out of tune.`;
    }

    if (score >= total * 0.4) {
        return `Good start, ${name}. Listen for the sour notes, then try again.`;
    }

    return `The orchestra needs rehearsal, ${name}. Have another go.`;
}

/** Player key used when the result dialog plays the current board. */
export const RESULT_PLAYER_KEY = 'result';
