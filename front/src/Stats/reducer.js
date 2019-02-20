import { values } from 'ramda';

export const entrepriseStat = (state = {}, action) => {
    switch (action.type) {
    case 'GET_ENTREPRISE_STAT_SUCCESS':
        return {
            ...state,
            ...action.entrepriseStat.reduce((stats, stat) => {
                stats.push({
                    name: stat["entreprise"],
                    montant_total: Number.parseInt(stat["montant_total"]),
                    nombre_commande: Number.parseInt(stat["nombre_commande"]),
                });
                return stats;
            }, []),
        };
    default:
        return state;
    }
};

export const entreeDessertRepasStat = (state = {}, action) => {
    switch (action.type) {
    case 'GET_ENTREE_DESSERT_REPAS_STATS_SUCCESS':
        let farr = [ ...action.entreeDessertRepasStat["entrees"], ...action.entreeDessertRepasStat["desserts"] ];
        return {
            ...state,
            ...farr.reduce((stats, stat) => {
                if (!(stat["repas"] in stats)) {
                    if ("entree" in stat) {
                        stats[stat["repas"]] = {
                            repas: stat["repas"],
                            entree: stat["entree"],
                            dessert: "",
                            nombre_vente_entree: stat["nombre_vente"],
                            nombre_vente_dessert: 0,
                        }
                    } else if ("dessert" in stat) {
                        stats[stat["repas"]] = {
                            repas: stat["repas"],
                            entree: "",
                            dessert: stat["dessert"],
                            nombre_vente_entree: 0,
                            nombre_vente_dessert: stat["nombre_vente"],
                        }
                    }
                } else {
                    if ("entree" in stat) {
                        if (stat["nombre_vente"] > stats[stat["repas"]].nombre_vente_entree) {
                            stats[stat["repas"]].nombre_vente_entree = stat["nombre_vente"];
                            stats[stat["repas"]].entree = stat["entree"];
                        }
                    } else if ("dessert" in stat) {
                        if (stat["nombre_vente"] > stats[stat["repas"]].nombre_vente_dessert) {
                            stats[stat["repas"]].nombre_vente_dessert = stat["nombre_vente"];
                            stats[stat["repas"]].dessert = stat["dessert"];
                        }
                    }
                }
                return stats;
            }, {}),
        };
    default:
        return state;
    }
}

export const repasStatsHor = (state = {}, action) => {
    switch (action.type) {
    case 'GET_REPAS_STATS_HOR_SUCCESS':
    return {
            ...state,
            ...action.repasStatsHor.reduce((stats, stat) => {
                stats.push({
                    name: parseInt(stat['horaireLivraison'], 10),
                    nombre_repas: parseInt(stat['nombre_repas'], 10),
                    montant: parseInt(stat['montant'], 10),
                });
                return stats;
            }, []),
        };
    default:
        return state;
    }
};

export const statsRepas = (state = {}, action) => {
  switch (action.type) {
    case 'GET_REPAS_STATS_SUCCESS':
      return {
        ...state,
        ...action.statsRepas.reduce((stats, stat) => {
          stats.push({
            name: stat['titre'],
            value: Number.parseInt(stat['nombre_repas'])
          });
          return stats;
        }, [])
      };
    case 'GET_REPAS_STATS_ERROR':
      return {
        ...state,
        ...action.error
      };
    default:
      return state;
  }
};

export const statsClientsNbRepas = (state = {}, action) => {
  switch (action.type) {
    case 'GET_CLIENTS_STATS_CIVI_ERROR':
      return {
        ...state,
        ...action.error
      };
    case 'GET_CLIENTS_STATS_CIVI_SUCCESS':
      return {
        ...state,
        ...action.statsClientsNbRepas.reduce((stats, stat) => {
          stats.push({
            name: stat['civilite'],
            nombre_commande: Number.parseInt(stat['nombre_commande']),
            montant_total: Number.parseInt(stat['montant_total'])
          });
          return stats;
        }, [])
      };
    default:
      return state;
  }
};

const find18 = object => {
  return object.name === 'Inférieur à 18 ans';
};
const find1825 = object => {
  return object.name === 'Entre 18 et 24 ans';
};
const find2539 = object => {
  return object.name === 'Entre 25 et 39 ans';
};
const find4054 = object => {
  return object.name === 'Entre 40 et 54 ans';
};
const find55 = object => {
  return object.name === 'Supérieur ou égal à 55 ans';
};

const initialValue = {
  nombre_commande: 0,
  montant_total: 0
};

const stats = {
  0: { name: 'Inférieur à 18 ans', ...initialValue },
  1: { name: 'Entre 18 et 24 ans', ...initialValue },
  2: { name: 'Entre 25 et 39 ans', ...initialValue },
  3: { name: 'Entre 40 et 54 ans', ...initialValue },
  4: { name: 'Supérieur ou égal à 55 ans', ...initialValue }
};

export const statsClientsAge = (state = {}, action) => {
  switch (action.type) {
    case 'GET_CLIENT_STATS_AGE_SUCCESS': {
      let newStats = stats;
      action.statsClientAge.map(stat => {
        if (stat.age < 18) {
          newStats = {
            ...newStats,
            0: {
              ...newStats[0],
              nombre_commande:
                newStats[0].nombre_commande +
                parseInt(stat.nombre_commande, 10),
              montant_total:
                newStats[0].montant_total + parseInt(stat.montant_total, 10)
            }
          };
        } else if (stat.age >= 18 && stat.age < 25) {
          newStats = {
            ...newStats,
            1: {
              ...newStats[1],
              nombre_commande:
                newStats[1].nombre_commande +
                parseInt(stat.nombre_commande, 10),
              montant_total:
                newStats[1].montant_total + parseInt(stat.montant_total, 10)
            }
          };
        } else if (stat.age >= 25 && stat.age < 40) {
          newStats = {
            ...newStats,
            2: {
              ...newStats[2],
              nombre_commande:
                newStats[2].nombre_commande +
                parseInt(stat.nombre_commande, 10),
              montant_total:
                newStats[2].montant_total + parseInt(stat.montant_total, 10)
            }
          };
        } else if (stat.age >= 40 && stat.age < 55) {
          newStats = {
            ...newStats,
            3: {
              ...newStats[3],
              nombre_commande:
                newStats[3].nombre_commande +
                parseInt(stat.nombre_commande, 10),
              montant_total:
                newStats[3].montant_total + parseInt(stat.montant_total, 10)
            }
          };
        } else if (stat.age >= 55) {
          newStats = {
            ...newStats,
            4: {
              ...newStats[4],
              nombre_commande:
                newStats[4].nombre_commande +
                parseInt(stat.nombre_commande, 10),
              montant_total:
                newStats[4].montant_total + parseInt(stat.montant_total, 10)
            }
          };
        }
      });
      return newStats;
    }
    case 'GET_CLIENT_STATS_AGE_ERROR':
      return {
        ...state,
        ...action.error
      };
    default:
      return state;
  }
};



export const statsRepasSelector = state => values(state.statsRepas);
export const statsClientsStatsCiviSelector = state => values(state.statsClientsNbRepas);
export const statsClientsAgeSelector = state => values(state.statsClientsAge);
export const statsRepasHorSelector = state => values(state.repasStatsHor);
export const entreeDessertRepasStatSelector = state => values(state.entreeDessertRepasStat);
export const entrepriseStatSelector = state => values(state.entrepriseStat);
