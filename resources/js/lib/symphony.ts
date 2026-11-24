/**
 * The symphony: one melody note per board slot, in board order.
 * A correct image plays its melody note; a wrong one plays a sour, detuned note.
 */
export const MELODY = [
    62, 66, 69, 74, 73, 71, 69, 66, 67, 71, 74, 71, 69, 66, 73, 74,
];

const CHORDS = [
    [50, 57, 62, 66],
    [47, 54, 59, 62],
    [43, 50, 55, 59],
    [45, 52, 57, 61],
    [43, 50, 55, 59],
    [45, 52, 57, 61],
    [50, 57, 62, 66],
];

const STEP_SECONDS = 0.4;

/** Bar height (percent) for a slot's note, used by the staff visualisations. */
export function noteHeight(index: number, base = 25): number {
    return Math.round(base + (MELODY[index] - 60) * 5);
}

export function patternToNotes(pattern: string): boolean[] {
    return pattern.split('').map((character) => character === '1');
}

const hz = (midi: number): number => 440 * Math.pow(2, (midi - 69) / 12);

function reverbImpulse(context: AudioContext): AudioBuffer {
    const length = context.sampleRate * 2.2;
    const buffer = context.createBuffer(2, length, context.sampleRate);

    for (let channel = 0; channel < 2; channel++) {
        const data = buffer.getChannelData(channel);

        for (let i = 0; i < length; i++) {
            data[i] = (Math.random() * 2 - 1) * Math.pow(1 - i / length, 2.6);
        }
    }

    return buffer;
}

function voice(
    context: AudioContext,
    output: AudioNode,
    midi: number,
    start: number,
    duration: number,
    isGood: boolean,
): void {
    const gain = context.createGain();
    const filter = context.createBiquadFilter();
    filter.type = 'lowpass';
    filter.frequency.value = isGood ? 2200 : 1100;
    filter.Q.value = isGood ? 0.7 : 4;

    const detune = isGood ? 6 : 28;

    [-detune, detune].forEach((cents) => {
        const oscillator = context.createOscillator();
        oscillator.type = 'sawtooth';
        oscillator.frequency.value = hz(midi);
        oscillator.detune.value = cents;
        oscillator.connect(filter);
        oscillator.start(start);
        oscillator.stop(start + duration + 0.6);
    });

    if (isGood) {
        const shimmer = context.createOscillator();
        const shimmerGain = context.createGain();
        shimmer.type = 'sine';
        shimmer.frequency.value = hz(midi + 12);
        shimmerGain.gain.setValueAtTime(0, start);
        shimmerGain.gain.linearRampToValueAtTime(0.05, start + 0.02);
        shimmerGain.gain.exponentialRampToValueAtTime(0.0001, start + duration);
        shimmer.connect(shimmerGain).connect(output);
        shimmer.start(start);
        shimmer.stop(start + duration + 0.1);
    }

    filter.connect(gain).connect(output);

    const peak = isGood ? 0.11 : 0.09;
    gain.gain.setValueAtTime(0, start);
    gain.gain.linearRampToValueAtTime(peak, start + 0.07);
    gain.gain.linearRampToValueAtTime(peak * 0.7, start + 0.25);
    gain.gain.exponentialRampToValueAtTime(0.0001, start + duration + 0.5);
}

function pad(
    context: AudioContext,
    output: AudioNode,
    notes: number[],
    start: number,
    duration: number,
    level: number,
): void {
    notes.forEach((midi) => {
        const oscillator = context.createOscillator();
        const gain = context.createGain();
        oscillator.type = 'triangle';
        oscillator.frequency.value = hz(midi);
        gain.gain.setValueAtTime(0, start);
        gain.gain.linearRampToValueAtTime(level, start + 0.35);
        gain.gain.setValueAtTime(level, start + duration - 0.2);
        gain.gain.linearRampToValueAtTime(0, start + duration + 0.4);
        oscillator.connect(gain).connect(output);
        oscillator.start(start);
        oscillator.stop(start + duration + 0.5);
    });
}

export type SymphonyPerformance = {
    stop: () => void;
};

/**
 * Play an arrangement. `onStep` fires as each slot's note sounds (index -1 when the melody is over),
 * `onEnd` once the final chord has faded or the performance is stopped.
 */
export function performSymphony(
    notes: boolean[],
    {
        onStep,
        onEnd,
    }: { onStep?: (index: number) => void; onEnd?: () => void } = {},
): SymphonyPerformance {
    const context = new AudioContext();
    const timers: number[] = [];
    let isStopped = false;

    const master = context.createGain();
    const compressor = context.createDynamicsCompressor();
    const reverb = context.createConvolver();
    const wet = context.createGain();
    master.gain.value = 0.9;
    reverb.buffer = reverbImpulse(context);
    wet.gain.value = 0.35;
    master.connect(compressor);
    master.connect(reverb);
    reverb.connect(wet).connect(compressor);
    compressor.connect(context.destination);

    const t0 = context.currentTime + 0.2;
    const lastIndex = notes.length - 1;
    const msUntil = (time: number): number =>
        (time - context.currentTime) * 1000;

    CHORDS.forEach((chord, bar) => {
        if (bar * 4 < notes.length) {
            pad(
                context,
                master,
                chord,
                t0 + bar * 4 * STEP_SECONDS,
                (bar === CHORDS.length - 1 ? 1 : 4) * STEP_SECONDS,
                0.028,
            );
        }
    });

    notes.forEach((isGood, index) => {
        const midi = isGood
            ? MELODY[index]
            : MELODY[index] + (index % 2 ? 1 : -1);
        const start = t0 + index * STEP_SECONDS;
        voice(
            context,
            master,
            midi,
            start,
            index === lastIndex ? STEP_SECONDS * 4 : STEP_SECONDS * 1.5,
            isGood,
        );
        timers.push(window.setTimeout(() => onStep?.(index), msUntil(start)));
    });

    const end = t0 + notes.length * STEP_SECONDS;

    if (notes.every(Boolean)) {
        [62, 66, 69, 74, 78, 81, 86].forEach((midi, k) =>
            voice(context, master, midi, end + k * 0.09, 2.2, true),
        );
        pad(context, master, [38, 50, 57, 62, 66], end, 2.5, 0.04);
    } else {
        pad(context, master, [38, 50, 57, 62], end, 2, 0.03);
    }

    const stop = (): void => {
        if (isStopped) {
            return;
        }

        isStopped = true;
        timers.forEach(clearTimeout);
        void context.close();
        onEnd?.();
    };

    timers.push(window.setTimeout(() => onStep?.(-1), msUntil(end) + 200));
    timers.push(window.setTimeout(stop, msUntil(end) + 3200));

    return { stop };
}
