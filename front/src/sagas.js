import OrdersSaga from './Orders/sagas';
import StatsSaga from './Stats/sagas';

export default function* IndexSaga() {
    yield [
        OrdersSaga(),
        StatsSaga(),
    ];
}
