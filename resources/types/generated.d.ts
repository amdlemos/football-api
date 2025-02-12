export type AreaData = {
    id: number;
    name: string;
    code: string;
};
export type CompetitionData = {
    id: number;
    name: string;
    code: string;
    type: string;
    emblem: string;
    plan: string;
    area: AreaData | null;
    currentSeason: SeasonData | null;
    teams: Array<TeamData> | null;
    published_at: string | null;
};
export type GameData = {
    id: number;
    utcDate: string;
    homeScoreFullTime: string | null;
    awayScoreFullTime: string | null;
    homeTeam: TeamData;
    awayTeam: TeamData;
};
export type SeasonData = {
    id: number;
    start_date: string;
    end_date: string;
};
export type TeamData = {
    id: number;
    name: string;
    shortname: string | null;
    tla: string | null;
    crest: string | null;
    address: string | null;
    website: string | null;
    founded: string | null;
    venue: string | null;
    clubColors: string | null;
};
