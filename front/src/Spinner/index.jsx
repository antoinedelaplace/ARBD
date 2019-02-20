import { withStyles } from 'vitaminjs';

import s from './style.css';

const Spinner = () =>
    <div className={s.spinner}>
        <div className={s.rect1} />
        <div className={s.rect2} />
        <div className={s.rect3} />
        <div className={s.rect4} />
        <div className={s.rect5} />
    </div>;

export default withStyles(s)(Spinner);
