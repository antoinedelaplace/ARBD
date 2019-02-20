import { withStyles } from 'vitaminjs';
import { Link } from 'vitaminjs/react-router';
import Helmet from 'vitaminjs/react-helmet';

import s from './style.css';
import logo from './logo.png';

const Index = ({ children }) =>
    <div className={s.app}>
        <Helmet
            title="Speed Bouffe"
            meta={[
                { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            ]}
        />
        <div className={s.menu}>
            <img style={{ width: 45, margin: '0 16px' }} src={logo} />
            <Link activeClassName={s.active} className={s.link} to="/">Orders</Link>
            <Link activeClassName={s.active} className={s.link} to="/stats">Stats</Link>
        </div>
        {children}
    </div>
;

export default withStyles(s)(Index);
