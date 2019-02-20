import { call, takeLatest, put } from 'redux-saga/effects';
import { getOrdersSuccess, getOrdersErrors, getOrderDetailsSuccess, getOrderDetailsError } from './actions';

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

function getOrderSAPI() {
    const url = `${baseUrl}/commandes`;
    const request = fetch(url, {
        method: 'GET',
        headers: {},
        // mode: 'no-cors',
        body: {},
    });
    return handleRequest(request);
}

function* getOrdersFlow(action) {
    try {
        const orders = yield call(getOrderSAPI);
        yield put(getOrdersSuccess(orders));
    } catch (error) {
        yield put(getOrdersErrors(error));
    }
}

function getOrderDetailsAPI(id) {
    const url = `${baseUrl}/commande/${id}`;
    const request = fetch(url, {
        method: 'GET',
        headers: {},
        body: {},
    });
    return handleRequest(request);
}

function* getOrderDetailsFlow(action) {
    try {
        const order = yield call(getOrderDetailsAPI, action.id);
        yield put(getOrderDetailsSuccess(order, action.id));
    } catch (error) {
        yield put(getOrderDetailsError(error));
    }
}

function* ordersWatcher() {
    yield [takeLatest('GET_ORDERS', getOrdersFlow)];
    yield [takeLatest('GET_ORDER_DETAILS', getOrderDetailsFlow)];
}

export default ordersWatcher;
