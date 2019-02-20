import { call, takeLatest, put } from 'redux-saga/effects';
import {
    getStatsRepasSuccess, getStatsRepasError,
    getStatsClientCiviSuccess, getStatsClientCiviError,
    getStatsClientAgeSuccess, getStatsClientError,
    getRepasStatsHorSuccess, getRepasStatsHorError,
    getEntreeDessertRepasStatsSuccess, getEntreeDessertRepasStatsError,
    getEntrepriseStatSuccess, getEntrepriseStatError,
} from './actions.js';

const baseUrl = 'http://localhost:9500';

function handleApiErrors(response) {
    if (!response.ok) {
        throw Error(response.statusText);
    }
    return response;
}

function handleRequest(request) {
    return request
        .then(handleApiErrors)
        .then(response => response.json())
        .then(json => json)
        .catch((error) => {
            throw error;
        });
}

function getEntrepriseStatAPI() {
    const url = `${baseUrl}/clients/stats/email`;
    const request = fetch(url, {
        methid: 'GET',
        headers: {},
        body: {},
    });
    return handleRequest(request);
}

function* getEntrepriseStatFlow(action) {
    try {
        const stats = yield call(getEntrepriseStatAPI);
        yield put(getEntrepriseStatSuccess(stats));
    } catch (error) {
        yield put(getEntrepriseStatError(error));
    }
}

function getStatsRepasAPI() {
    const url = `${baseUrl}/repas/stats`;
    const request = fetch(url, {
        method: 'GET',
        headers: {},
        body: {},
    });
    return handleRequest(request);
}

function getEntreeDessertRepasAPI() {
    const url = `${baseUrl}/repas/classement`;
    const request = fetch(url, {
        method: 'GET',
        headers: {},
        body: {},
    });
    return handleRequest(request);
}

function* getStatsRepasFlow(action) {
    try {
        const stats = yield call(getStatsRepasAPI);
        yield put(getStatsRepasSuccess(stats));
    } catch (error) {
        yield put(getStatsRepasError(error));
    }
}

function* getEntreeDessertRepasStatsFlow(action) {
    try {
        const stats = yield call(getEntreeDessertRepasAPI);
        yield put(getEntreeDessertRepasStatsSuccess(stats));
    } catch (error) {
        yield put(getEntreeDessertRepasStatsError(error));
    }
}

function getRepasStatsHorAPI() {
    const url = `${baseUrl}/commandes/stats/horaire`;
    const request = fetch(url, {
        method: 'GET',
        headers: {},
        body: {},
    });
    return handleRequest(request);
}

function getStatsClientsCiviAPI() {
    const url = `${baseUrl}/clients/stats/civilite`;
    const request = fetch(url, {
        method: 'GET',
        headers: {},
        body: {},
    });
    return handleRequest(request);
}

function getStatsClientsAgeAPI() {
    const url = `${baseUrl}/clients/stats/age`;
    const request = fetch(url, {
        method: 'GET',
        headers: {},
        body: {},
    });
    return handleRequest(request);
}

function* getStatsClientCiviFlow(action) {
    try {
        const stats = yield call(getStatsClientsCiviAPI);
        yield put(getStatsClientCiviSuccess(stats));
    } catch (error) {
        yield put(getStatsClientCiviError(error));
    }
}

function* getStatsClientsAgeFlow(action) {
    try {
        const stats = yield call(getStatsClientsAgeAPI);
        yield put(getStatsClientAgeSuccess(stats));
    } catch (error) {
        yield put(getStatsClientError(error));
    }
}

function* getRepasStatsHorFlow(action) {
    try {
        const stats = yield call(getRepasStatsHorAPI);
        yield put(getRepasStatsHorSuccess(stats));
    } catch (error) {
        yield put(getRepasStatsHorError(error));
    }
}

function* statsWatcher() {
    yield [takeLatest('GET_REPAS_STATS', getStatsRepasFlow)];
    yield [takeLatest('GET_CLIENTS_STATS_CIVI', getStatsClientCiviFlow)];
    yield [takeLatest('GET_CLIENTS_STATS_AGE', getStatsClientsAgeFlow)];
    yield [takeLatest('GET_REPAS_STATS_HOR', getRepasStatsHorFlow)];
    yield [takeLatest('GET_ENTREE_DESSERT_REPAS_STATS', getEntreeDessertRepasStatsFlow)];
    yield [takeLatest('GET_ENTREPRISE_STAT', getEntrepriseStatFlow)];
}

export default statsWatcher;
