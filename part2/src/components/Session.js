import React from 'react';
import Presentation from './Presentation.js';

/**
 * Displays details about a session
 * 
 * @author Vlad Cocis
 * 
 */
class Session extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            sessionDetails: {},
            loading: true,
            display: false,
        };
    }

    /**
     * Decodes JSON of sessions based on sessionId from API
     */
    loadSessionData = () => {
        this.setState({ display: !this.state.display });
        const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/session-content?sessionId=" + this.props.details.sessionId;
        fetch(url)
            .then((response) => response.json())
            .then((data) => {
                this.setState({ sessionDetails: data.data, loading: false });
            })
            .catch((err) => console.error(err));

        console.log(this.state.sessionDetails);
    };

    /**
     * Shows all presentations in a session
     */
    showSessionData = () => {
        console.log(this.state.sessionDetails);

        return this.state.sessionDetails.map((details, index) => {
            return (
                <div key={index}>
                    <Presentation details={details} />
                </div>
            );
        });
    };

    render = () => {
        return (
            <div>
                <h3 onClick={this.loadSessionData}>
                    {this.props.details.name}
                    
                    
                </h3>
                <p>{this.props.details.chair}</p>
                    <p>Type: {this.props.details.type} </p>
                    <p>Room: {this.props.details.room}</p>
                
                {this.state.display ? (
                    <div>
                        Session Content: 
                        {this.state.loading ? <p>Loading...</p> : this.showSessionData()}
                    </div>
                ) : null}
            </div>
        );
    };
}
export default Session;