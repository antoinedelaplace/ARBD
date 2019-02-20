import 'regenerator-runtime/runtime';
import { Route } from 'vitaminjs/react-router';
import Orders from './Orders';
import Stats from './Stats';
import sagaMiddleware from './middlewares/saga';
import IndexSagas from './sagas';
import Index from './Index';

function runSaga() {
    if (IS_CLIENT) {
        if (window.__SAGA_TASK__) window.__SAGA_TASK__.cancel();
        window.__SAGA_TASK__ = sagaMiddleware.run(IndexSagas);
    } else {
        sagaMiddleware.run(IndexSagas);
    }
}

if (process.env.NODE_ENV !== 'production') {
    (IS_CLIENT ? window : global).trace = (...logs) => x => console.log(...logs, x) || x;
}

export default async (store) => {
    runSaga();
    return (
        <Route component={Index}>
            <Route path="/" component={Orders} />
            <Route path="/stats" component={Stats} />
        </Route>
    );
};
