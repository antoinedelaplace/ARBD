import { Component } from 'react';
import { PropTypes } from 'prop-types';
import { compose } from 'vitaminjs/redux';
import { connect } from 'vitaminjs/react-redux';
import autobind from 'autobind-decorator';
import { withStyles } from 'vitaminjs';
import { PieChart, Pie, Legend, Tooltip, Cell, BarChart, Bar, XAxis, YAxis } from 'recharts';
import { isEmpty } from 'ramda';

import {
    getRepasStats,
    getClientsStatsCivi,
    getClientsStatsAge,
    getRepasStatsHor,
    getEntreeDessertRepasStats,
    getEntrepriseStat,
} from './actions';
import {
    statsRepasSelector,
    statsClientsStatsCiviSelector,
    statsClientsAgeSelector,
    statsRepasHorSelector,
    entreeDessertRepasStatSelector,
    entrepriseStatSelector,
} from './reducer';

import s from './style.css';

const COLORS = [
    '#880E4F',
    '#AD1457',
    '#C2185B',
    '#D81B60',
    '#E91E63',
    '#EC407A',
    '#F06292',
    '#F48FB1',
    '#F8BBD0',
];

class Stats extends Component {
    static propTypes = {
        fetchStatsRepas: PropTypes.func.isRequired,
        fetchClientsStatsRepas: PropTypes.func.isRequired,
        fetchClientsStatsAge: PropTypes.func.isRequired,
        fetchEntrepriseStat: PropTypes.func.isRequired,
        statsRepas: PropTypes.arrayOf(PropTypes.object).isRequired,
        statsClientsNbRepas: PropTypes.arrayOf(PropTypes.object).isRequired,
        statsClientAge: PropTypes.arrayOf(PropTypes.object).isRequired,
        repasStatsHor: PropTypes.arrayOf(PropTypes.object).isRequired,
        entreeDessertRepasStat: PropTypes.objectOf(PropTypes.object).isRequired,
    };

    constructor(props) {
        super(props);
        this.props.fetchStatsRepas();
        this.props.fetchClientsStatsRepas();
        this.props.fetchClientsStatsAge();
        this.props.fetchRepasStatsHor();
        this.props.fetchEntreeDessertRepasStat();
        this.props.fetchEntrepriseStat();
    }

    render() {
        return (
            <div className={s.container}>
                {this.props.statsRepas.length !== 0 &&
                    <div className={s.stat}>
                        <h2>Stats repas</h2>
                        <PieChart width={300} height={300}>
                            <Pie
                                dataKey="value"
                                data={this.props.statsRepas}
                                outerRadius={120}
                                fill="#82ca9d"
                                label
                                labelLine={false}
                            >
                                {this.props.statsRepas.map((entry, index) =>
                                    <Cell fill={COLORS[index % COLORS.length]} />,
                                )}
                            </Pie>
                            <Tooltip />
                        </PieChart>
                    </div>}
                {this.props.statsClientsNbRepas.length !== 0 &&
                    <div className={s.stat}>
                        <h2>Nombre de commandes par type de client</h2>
                        <BarChart
                            width={400}
                            height={200}
                            margin={{
                                top: 5,
                                right: 30,
                                left: 20,
                                bottom: 5,
                            }}
                            data={this.props.statsClientsNbRepas}
                        >
                            <XAxis dataKey="name" />
                            <YAxis />
                            <Tooltip />
                            <Legend />
                            <Bar dataKey="nombre_commande" fill="#FE5B5F" />
                        </BarChart>
                    </div>}
                {this.props.statsClientsNbRepas.length !== 0 &&
                    <div className={s.stat}>
                        <h2>Montant total par type de client</h2>
                        <BarChart
                            width={400}
                            height={200}
                            margin={{
                                top: 5,
                                right: 30,
                                left: 20,
                                bottom: 5,
                            }}
                            data={this.props.statsClientsNbRepas}
                        >
                            <XAxis dataKey="name" />
                            <YAxis />
                            <Tooltip />
                            <Legend />
                            <Bar dataKey="montant_total" fill="#FE5B5F" />
                        </BarChart>
                    </div>}
                {!isEmpty(this.props.entreeDessertRepasStat) &&
                    <div className={s.stat}>
                        <h2>Entrées et désserts les plus commandés par repas</h2>
                        <table>
                            <tr>
                                <th>Repas</th>
                                <th>Entree</th>
                                <th>Dessert</th>
                            </tr>
                            {this.props.entreeDessertRepasStat.map(e => {
                                return (
                                    <tr>
                                        <td>
                                            {e.repas}
                                        </td>
                                        <td>
                                            {e.entree}
                                        </td>
                                        <td>
                                            {e.dessert}
                                        </td>
                                    </tr>
                                );
                            })}
                        </table>
                    </div>
                }
                {this.props.repasStatsHor.length !== 0 &&
                    <div className={s.stat}>
                        <h2>Nombre de commandes par heures</h2>
                        <BarChart
                            width={400}
                            height={200}
                            margin={{
                                top: 5,
                                right: 30,
                                left: 20,
                                bottom: 5,
                            }}
                            data={this.props.repasStatsHor}
                        >
                            <XAxis dataKey="name" />
                            <YAxis />
                            <Tooltip />
                            <Legend />
                            <Bar dataKey="nombre_repas" fill="#FE5B5F" />
                        </BarChart>
                    </div>}
                {this.props.repasStatsHor.length !== 0 &&
                    <div className={s.stat}>
                        <h2>Montants par heures</h2>
                        <BarChart
                            width={400}
                            height={200}
                            margin={{
                                top: 5,
                                right: 30,
                                left: 20,
                                bottom: 5,
                            }}
                            data={this.props.repasStatsHor}
                        >
                            <XAxis dataKey="name" />
                            <YAxis />
                            <Tooltip />
                            <Legend />
                            <Bar dataKey="montant" fill="#FE5B5F" />
                        </BarChart>
                    </div>}
                {this.props.statsClientAge.length !== 0 &&
                    <div>
                        <div>
                            <h2>Nombre de commandes par tranche d'age</h2>
                            <BarChart
                                width={1000}
                                height={200}
                                margin={{
                                    top: 5,
                                    right: 30,
                                    left: 20,
                                    bottom: 5,
                                }}
                                data={this.props.statsClientAge}
                            >
                                <XAxis dataKey="name" />
                                <YAxis />
                                <Tooltip />
                                <Legend />
                                <Bar dataKey="nombre_commande" fill="#FE5B5F" />
                            </BarChart>
                        </div>
                        <div>
                            <h2>Montant total par tranche d'âge</h2>
                            <BarChart
                                width={1000}
                                height={200}
                                margin={{
                                    top: 5,
                                    right: 30,
                                    left: 20,
                                    bottom: 5,
                                }}
                                data={this.props.statsClientAge}
                            >
                                <XAxis dataKey="name" />
                                <YAxis />
                                <Tooltip />
                                <Legend />
                                <Bar dataKey="montant_total" fill="#FE5B5F" />
                            </BarChart>
                        </div>
                    </div>}
                    {this.props.entrepriseStat !== 0 &&
                    <div>
                        <h2>nombre de commandes par entreprise</h2>
                        <div>
                            <BarChart
                                width={1000}
                                height={200}
                                margin={{
                                    top: 5,
                                    right: 30,
                                    left: 20,
                                    bottom: 5,
                                }}
                                data={this.props.entrepriseStat}>
                                <XAxis dataKey="name" />
                                <YAxis />
                                <Tooltip />
                                <Legend />
                                <Bar dataKey="nombre_commande" fill="#FE5B5F" />
                            </BarChart>
                        </div>
                        <h2>Montant total par entreprise</h2>
                        <div>
                            <BarChart
                                width={1000}
                                height={200}
                                margin={{
                                    top: 5,
                                    right: 30,
                                    left: 20,
                                    bottom: 5,
                                }}
                                data={this.props.entrepriseStat}>
                                <XAxis dataKey="name" />
                                <YAxis />
                                <Tooltip />
                                <Legend />
                                <Bar dataKey="montant_total" fill="#FE5B5F" />
                            </BarChart>
                        </div>
                    </div>}
            </div>
        );
    }
}

const mapStateToProps = state => ({
    statsRepas: statsRepasSelector(state),
    statsClientsNbRepas: statsClientsStatsCiviSelector(state),
    statsClientAge: statsClientsAgeSelector(state),
    repasStatsHor: statsRepasHorSelector(state),
    entreeDessertRepasStat: entreeDessertRepasStatSelector(state),
    entrepriseStat: entrepriseStatSelector(state),
    fetchStatsRepas: getRepasStats,
    fetchClientsStatsRepas: getClientsStatsCivi,
    fetchClientsStatsAge: getClientsStatsAge,
    fetchRepasStatsHor: getRepasStatsHor,
    fetchEntreeDessertRepasStat: getEntreeDessertRepasStats,
    fetchEntrepriseStat: getEntrepriseStat,
});

const mapDispatchToProps = dispatch => ({
    fetchStatsRepas() {
        dispatch(getRepasStats());
    },
    fetchClientsStatsRepas() {
        dispatch(getClientsStatsCivi());
    },
    fetchClientsStatsAge() {
        dispatch(getClientsStatsAge());
    },
    fetchRepasStatsHor() {
        dispatch(getRepasStatsHor());
    },
    fetchEntreeDessertRepasStat() {
        dispatch(getEntreeDessertRepasStats());
    },
    fetchEntrepriseStat() {
        dispatch(getEntrepriseStat());
    }
});

export default compose(connect(mapStateToProps, mapDispatchToProps), withStyles(s), autobind)(
    Stats,
);
