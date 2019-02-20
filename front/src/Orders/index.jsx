import { Component, PropTypes } from 'react';
import { withStyles } from 'vitaminjs';
import { connect } from 'vitaminjs/react-redux';
import autobind from 'autobind-decorator';
import { compose } from 'vitaminjs/redux';

import { getOrders, getOrderDetails } from './actions';
import { ordersSelector } from './reducer';
import s from './style.css';
import Spinner from '../Spinner';

class Orders extends Component {
    static propTypes = {
        fetchOrders: PropTypes.func.isRequired,
        fetchOrderDetails: PropTypes.func.isRequired,
    };

    constructor(props) {
        super(props);
        this.state = {
            selectedOrder: null,
            page: 1,
        };
        this.handleRefresh();
    }

    handleRefresh() {
        const { fetchOrders } = this.props;
        fetchOrders();
        setInterval(() => {
            fetchOrders();
        }, 30000);
    }

    handleOrderDetail(id) {
        this.props.fetchOrderDetails(id);
        this.setState({ selectedOrder: id });
        document.body.style.overflow = 'hidden';
    }

    handleClosePopup() {
        this.setState({ selectedOrder: null });
        document.body.style.overflow = 'initial';
    }

    handleNavigate(page) {
        this.setState({ page });
    }

    render() {
        const { orders } = this.props;
        const { page } = this.state;
        const ordersToDisplay = orders.slice(20 * (page - 1), 20 * page);
        const maxPage = Math.ceil(orders.length / 20);
        const selectedOrder = orders.find(order => order.id === this.state.selectedOrder);
        return (
            <div className={s.container}>
                {selectedOrder &&
                    <div>
                        <div className={s.shadow}>
                            <div className={s.popup}>
                                <button onClick={this.handleClosePopup} className={s.cross}>
                                    ✖
                                </button>
                                <div style={{ fontSize: 24, marginBottom: 16 }}>Détail de la commande</div>
                                <div style={{ height: 32 }}>{selectedOrder.nom} {selectedOrder.prenom}</div>
                                <div style={{ display: 'flex', justifyContent: 'flex-start', height: 32 }}>
                                    <div style={{ marginRight: 16 }}>Statut:{selectedOrder.etat}</div>
                                    <div>{`Livraison le ${selectedOrder.dateLivraison} à ${selectedOrder.horaireLivraison}`}</div>
                                </div>
                                <div style={{ height: 32 }}>{`Paiement: ${selectedOrder.prix_total}€ par ${selectedOrder.typePaiement}`}</div>

                                {selectedOrder.ligneCommande
                                    ? <div>
                                          <div className={s.tr}>
                                              <div className={s.td}>Repas</div>
                                              <div className={s.td}>Entree</div>
                                              <div className={s.td}>Dessert</div>
                                          </div>
                                          {selectedOrder.ligneCommande.map(
                                              commande =>
                                                  <div
                                                      className={s.tr}
                                                      key={`${commande.nom}${commande.prenom}`}
                                                  >
                                                      <div className={s.td}>
                                                          {commande.repas}
                                                      </div>
                                                      <div className={s.td}>
                                                          {commande.entree}
                                                      </div>
                                                      <div className={s.td}>
                                                          {commande.dessert}
                                                      </div>
                                                  </div>,
                                          )}
                                      </div>
                                    : <Spinner />}
                            </div>
                        </div>
                    </div>}
                <div>
                    <div style={{ backgroundColor: '#ff9799', marginTop: 32 }} className={s.tr}>
                        <div className={s.td}>ID</div>
                        <div className={s.td}>Nom</div>
                        <div className={s.td}>Prénom</div>
                        <div className={s.td}>Nombre repas</div>
                        <div className={s.td}>Livraison</div>
                        <div className={s.td}>Prix total</div>
                    </div>
                    {ordersToDisplay.map(order =>
                        <div
                            className={s.tr}
                            onClick={() => this.handleOrderDetail(order.id)}
                            key={order.id}
                        >
                            <div className={s.td}>
                                {order.id}
                            </div>
                            <div className={s.td}>
                                {order.nom}
                            </div>
                            <div className={s.td}>
                                {order.prenom}
                            </div>
                            <div className={s.td}>
                                {order.nombre_repas}
                            </div>
                            <div
                                className={s.td}
                            >{`${order.dateLivraison} - ${order.horaireLivraison}`}</div>
                            <div className={s.td}>
                                {order.prix_total}
                            </div>
                        </div>,
                    )}
                    <div style={{ display: 'flex', justifyContent: 'center' }}>
                        <button className={s.paginator} onClick={() => this.handleNavigate(1)}>
                            {'<<'}
                        </button>
                        {page > 1 &&
                            <button
                                className={s.paginator}
                                onClick={() => this.handleNavigate(page - 1)}
                            >
                                {'<'}
                            </button>}
                        {page > 2 &&
                            <button
                                className={s.paginator}
                                onClick={() => this.handleNavigate(page - 2)}
                            >
                                {page - 2}
                            </button>}
                        {page > 1 &&
                            <button
                                className={s.paginator}
                                onClick={() => this.handleNavigate(page - 1)}
                            >
                                {page - 1}
                            </button>}
                        <button className={s.paginator}>
                            {page}
                        </button>
                        {maxPage > page &&
                            <button
                                className={s.paginator}
                                onClick={() => this.handleNavigate(page + 1)}
                            >
                                {page + 1}
                            </button>}
                        {maxPage > page + 1 &&
                            <button
                                className={s.paginator}
                                onClick={() => this.handleNavigate(page + 2)}
                            >
                                {page + 2}
                            </button>}
                        {maxPage > page &&
                            <button
                                className={s.paginator}
                                onClick={() => this.handleNavigate(page + 1)}
                            >
                                {'>'}
                            </button>}
                        <button
                            className={s.paginator}
                            onClick={() => this.handleNavigate(maxPage)}
                        >
                            {'>>'}
                        </button>
                    </div>
                </div>
            </div>
        );
    }
}

const mapStateToProps = state => ({
    orders: ordersSelector(state),
    selectedOrder: null,
});

const mapDispatchToProps = dispatch => ({
    fetchOrders() {
        dispatch(getOrders());
    },

    fetchOrderDetails(id) {
        dispatch(getOrderDetails(id));
    },
});

export default compose(connect(mapStateToProps, mapDispatchToProps), withStyles(s), autobind)(
    Orders,
);
