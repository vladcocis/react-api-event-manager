import React from 'react';
import styles from '../schedule.module.css';
import Window from './Window.js'

/**
 * Displays a day, with all it's sessions and breaks
 * 
 * @author Vlad Cocis
 * 
 */
class Day extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            data: {},
            showWindow: false,
            windowDetails: [],
            display: false,
        };
    }

    /**
     * Decodes JSON of all sessions in a given day
     */
    componentDidMount() {
        const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/day-details?day=" + this.props.dayInt;
        fetch(url)
            .then((response) => response.json())
            .then((data) => {
                this.setState({ data: data.data });
            })
            .catch((err) => {
                console.log("something went wrong ", err);
            });
    }

    /**
     * Displays a window containing details about the session
     */
    toggleWindow = (e) => {
        this.setState({ showWindow: !this.state.showWindow });

        if (e.target.attributes.type && e.target.attributes.slotid) {
            const url =
                "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/slot-details?slotId=" + e.target.attributes.slotid.value;
            fetch(url)
                .then((response) => response.json())
                .then((data) => {
                    this.setState({ windowDetails: data.data });
                })
                .catch((err) => {
                    console.log("something went wrong ", err);
                });
        }
    };

    /**
     * Format time properly
     */
    displayTime = (hour, mins) => {
        let time = "";
        if (hour.length === 1) {
            time = "0" + String(hour) + ":";
        } else {
            time = String(hour) + ":";
        }
        if (mins.length === 1) {
            time += String(mins) + "0";
        } else {
            time += String(mins);
        }
        return time;
    };

    /**
     * Display data about day with all sessions and breaks
     */
    displayData = () => {
        return this.state.data.map((details, index) => {
            return (
                <tr key={index}>
                    <td type={details.type} slotid={details.slotId} onClick={this.toggleWindow}>
                        {details.type}
                    </td>
                    <td>
                        {`${this.displayTime(
                            details.startHour,
                            details.startMinute
                        )} - ${this.displayTime(details.endHour, details.endMinute)}`}
                    </td>
                </tr>
            );
        });
    };

    /**
     * Reveal sessions and breaks on day click
     */
    handleDayReveal = () => {
        this.setState({ display: !this.state.display });
    };

    render = () => {
        return (
            <><div className={styles.buttonDiv}>
                <button onClick={this.handleDayReveal} className={styles.showDay}>
                    {!this.state.display
                        ? `Show ${this.props.dayString}`
                        : `Hide ${this.props.dayString}`}
                </button>
            </div>
                {this.state.showWindow ? (
                    <Window
                        slotData={this.state.windowDetails}
                        closeWindow={this.toggleWindow}
                    />
                ) : null}

                {this.state.display ? (
                    <div className={styles.tableDiv}>
                        <table className={styles.schedule} key={this.props.dayInt}>
                            <thead>
                                <tr>
                                    <td colSpan={2}>{this.props.dayString}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>Time</td>
                                </tr>
                            </thead>

                            <tbody>
                                {this.displayData()}
                                <tr>
                                    <td colSpan={2}>Click session type for details</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ) : null}

            </>
        );
    };
}
export default Day;