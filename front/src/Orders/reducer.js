import { values } from 'ramda';

const orders = (state = { list: {} }, action) => {
    switch (action.type) {
    case 'GET_ORDERS_SUCCESS': {
        const l = state.list;
        return {
            list: {
                ...state.list,
                ...action.orders.reduce((acc, order) => {
                    if (!acc[order.id]) {
                        acc[order.id] = order;
                    }
                    return acc;
                }, l),
            },
        };
    }
    case 'GET_ORDER_DETAILS_SUCCESS':
        return {
            list: {
                ...state.list,
                [action.orderId]: {
                    ...state.list[action.orderId],
                    ...action.orderDetails.commande,
                    ligneCommande: action.orderDetails.ligneCommande,
                },
            },
        };
    default:
        return state;
    }
};

export const ordersSelector = state => values(state.orders.list);

export default orders;
