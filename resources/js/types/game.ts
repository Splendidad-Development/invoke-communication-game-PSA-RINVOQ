export type Player = {
    id: number;
    name: string;
};

export type Group = {
    number: number;
    persona: string;
    question: string;
};

export type SectionTone = 'unmet' | 'rtb' | 'ctrl' | 'rct' | 'rwe' | 'add';

export type ColumnTone = 'unmet' | 'how' | 'simpl';

export type BoardSection = {
    key: string;
    title: string;
    tone: SectionTone;
    slots: string[];
};

export type BoardColumn = {
    key: string;
    tone: ColumnTone;
    title: string;
    description: string | null;
    rows: BoardSection[][];
};

export type GameImage = {
    id: number;
    url: string;
};

export type ArrangementResult = {
    id: number;
    placements: Record<string, number>;
    /** One character per slot in board order: "1" correct, "0" wrong. */
    pattern: string;
    score: number;
    /** The boxes the player got wrong, in board order. */
    mistakes: Mistake[];
    /** The correct board: slot id => image id. */
    solution: Record<string, number>;
};

export type Mistake = {
    slotId: string;
    /** The image the player put in this box. */
    imageId: number | null;
    /** Title of the box the image was put in. */
    placedIn: string;
    /** Title of the box the image belongs in. */
    belongsIn: string | null;
};

export type LeaderboardEntry = {
    id: number;
    playerId: number;
    name: string;
    group: number;
    score: number;
    pattern: string;
    mine: boolean;
};

export type GroupCard = {
    number: number;
    /** Caption under the group's card; bold parts are highlighted. */
    caption: { text: string; bold: boolean }[];
};
