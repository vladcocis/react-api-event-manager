import React from 'react';
import UpdateItem from './UpdateItem.js';

/**
 * Creates the update page, accesible only by logged in users
 * 
 * @author Vlad Cocis
 * 
 */
class Update extends React.Component {
    state = { data: [] }

    /**
     * Decodes JSON of sessions based from API
     */
    componentDidMount() {
        const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/sessions"
        fetch(url)
            .then((response) => response.json())
            .then((data) => {
                this.setState({ data: data.data })
            })
            .catch((err) => {
                console.log("something went wrong ", err)
            }
            );
    }
    render() {
        return (
            <div>
                {this.state.data.map((details, i) => (<UpdateItem key={i} details={details} handleUpdateClick={this.props.handleUpdateClick} />))}
            </div>
        );
    }
}

export default Update;