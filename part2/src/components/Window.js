import React from 'react';
import Session from './Session.js';
import styles from '../window.module.css';

/**
 * Creates a window displaying details of a specific session
 * 
 * @author Vlad Cocis
 * 
 */
class Window extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            display: false,
        };

        console.log(this.props.slotData);
    }

    /**
     * Shows all details of the session if session name is clicked
     */
    displaySlotData = () => {
        return this.props.slotData.map((details, index) => {
            return <Session key={`${index}${details.sessionId}`} details={details} />;
        });
    };

    render = () => {
        return (
            <div className={styles.window}>
                <div className={styles.window_inner}>
                    <button className={styles.close_window_button} onClick={this.props.closeWindow}>
                        Close
                    </button>
                    Click session title to show more details
                    {this.displaySlotData()}
                </div>
            </div>
        );
    };
}
export default Window;