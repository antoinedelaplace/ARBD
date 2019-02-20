export const getRepasStats = () => ({
    type: 'GET_REPAS_STATS',
});

export const getClientsStatsCivi = () => ({
    type: 'GET_CLIENTS_STATS_CIVI',
});

export const getClientsStatsAge = () => ({
    type: 'GET_CLIENTS_STATS_AGE',
});

export const getRepasStatsHor = () => ({
    type: 'GET_REPAS_STATS_HOR',
});

export const getEntrepriseStat = () => ({
    type: 'GET_ENTREPRISE_STAT',
});

export const getEntreeDessertRepasStats = () => ({
    type: 'GET_ENTREE_DESSERT_REPAS_STATS',
});

export const getEntrepriseStatSuccess = entrepriseStat => ({
    type: 'GET_ENTREPRISE_STAT_SUCCESS',
    entrepriseStat,
});

export const getEntrepriseStatError = error => ({
    type: 'GET_ENTREPRISE_STAT_ERROR',
    error,
});

export const getEntreeDessertRepasStatsSuccess = entreeDessertRepasStat => ({
    type: 'GET_ENTREE_DESSERT_REPAS_STATS_SUCCESS',
    entreeDessertRepasStat,
});

export const getEntreeDessertRepasStatsError = error => ({
    type: 'GET_ENTREE_DESSERT_REPAS_STATS_ERROR',
    error,
});

export const getRepasStatsHorSuccess = repasStatsHor => ({
    type: 'GET_REPAS_STATS_HOR_SUCCESS',
    repasStatsHor,
});

export const getRepasStatsHorError = error => ({
    type: 'GET_REPAS_STATS_HOR_ERROR',
    error,
});

export const getStatsClientCiviSuccess = statsClientsNbRepas => ({
    type: 'GET_CLIENTS_STATS_CIVI_SUCCESS',
    statsClientsNbRepas,
});

export const getStatsClientCiviError = error => ({
    type: 'GET_CLIENTS_STATS_CIVI_ERROR',
    error,
});

export const getStatsRepasSuccess = statsRepas => ({
    type: 'GET_REPAS_STATS_SUCCESS',
    statsRepas,
});

export const getStatsRepasError = error => ({
    type: 'GET_REPAS_STATS_ERROR',
    error,
});

export const getStatsClientAgeSuccess = statsClientAge => ({
    type: 'GET_CLIENT_STATS_AGE_SUCCESS',
    statsClientAge,
});

export const getStatsClientError = error => ({
    type: 'GET_CLIENT_STATS_AGE_ERROR',
    error,
});
