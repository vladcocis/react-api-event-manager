import React from 'react';
import Day from './Day';
import styles from '../schedule.module.css'

/**
 * Creates the schedule page
 * 
 * @author Vlad Cocis
 * 
 */
class Schedule extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
            slots: {},
            loading: true
        }
    }

    /**
     * Decodes JSON of days from API
     */
    componentDidMount() {
        const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/days"
        fetch(url)
          .then((response) => response.json())
          .then((data) => {
            this.setState({ slots: data.data, loading: false })
          })
          .catch((err) => {
            console.log("something went wrong ", err)
          }
          );
      }
    
    /**
     * Displays buttons for all days
     */
    displayData = () => {
        return this.state.slots.map((it, index) => {
            return (
                <Day key={index} dayInt={it.dayInt} dayString={it.dayString} />
            )
        })
    }

    render = () => {
        return (
            <div>
                <h1>Schedule Page</h1>

                <div className={styles.day}>
                    { this.state.loading ? <p>Loading...</p> : this.displayData() }
                </div>
            </div>
        )
    }
}

export default Schedule