import React from 'react';

/**
 * Updates a session title if the user is logged in and has admin status
 * 
 * @author Vlad Cocis
 * 
 */
class UpdateItem extends React.Component {

    state = { session_name: this.props.details.session_name }

    /**
    * Shows changes live in text box
    */
    handleNameChange = (e) => {
        this.setState({ session_name: e.target.value })
    }

    /**
    * Updates session with new name in database
    */
    handleNameUpdate = () => {
        this.props.handleUpdateClick(this.props.details.sessionId, this.state.session_name)
    }

    render() {
        return (
            <div>
                Session Name:<textarea
                    rows="4" cols="50"
                    value={this.state.session_name}
                    onChange={this.handleNameChange}
                />
                <button onClick={this.handleNameUpdate}>Update</button>
            </div>
        );
    }
}

export default UpdateItem;