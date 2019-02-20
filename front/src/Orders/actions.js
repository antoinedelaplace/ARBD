export const getOrders = () => ({
    type: 'GET_ORDERS',
});

export const getOrdersSuccess = orders => ({
    type: 'GET_ORDERS_SUCCESS',
    orders,
});

export const getOrdersErrors = error => ({
    type: 'GET_ORDERS_ERROR',
    error,
});

export const getOrderDetails = id => ({
    type: 'GET_ORDER_DETAILS',
    id,
});

export const getOrderDetailsSuccess = (orderDetails, orderId) => ({
    type: 'GET_ORDER_DETAILS_SUCCESS',
    orderDetails,
    orderId,
});

export const getOrderDetailsError = orderDetails => ({
    type: 'GET_ORDER_DETAILS_ERROR',
    orderDetails,
});
