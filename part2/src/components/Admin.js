import React from 'react';
import Login from './Login.js';
import Update from './Update.js';

/**
 * Provides administrator functionality for logged in users
 * 
 * @author Vlad Cocis
 * 
 */
class Admin extends React.Component {

  constructor(props) {
    super(props);
    this.state = { "authenticated": false, "email": "", "password": "" }

    this.handleEmail = this.handleEmail.bind(this);
    this.handlePassword = this.handlePassword.bind(this);

  }

  state = { "authenticated": false, "email": "", "password": "" }

  /**
   * Checks if token is present in localStorage
   * 
   */
  componentDidMount() {
    if (localStorage.getItem('myToken')) {
      this.setState({ "authenticated": true });
    }
  }

  /**
   * Decodes JSON
   * 
   * @param url - URL where JSON is found
   * @param myJSON - JSON data
   * @param callback - callback function
   * 
   */
  postData = (url, myJSON, callback) => {
    fetch(url, {
      method: 'POST',
      headers: new Headers(),
      body: JSON.stringify(myJSON)
    })
      .then((response) => response.json())
      .then((data) => {
        callback(data)
      })
      .catch((err) => {
        console.log("something went wrong ", err)
      }
      );
  }

  /**
   * Callback function for login functionality
   * 
   * @param data - JSON response
   * 
   */
  loginCallback = (data) => {
    console.log(data)
    if (data.status === 200) {
      this.setState({ "authenticated": true })
      localStorage.setItem('myToken', data.token);
    }
  }

  /**
   * Callback function for update functionality
   * 
   * @param data - JSON response
   * 
   */
  updateCallback = (data) => {
    console.log(data)
    if (data.status !== 200 && data.status !== 403) {
      this.setState({ "authenticated": false })
      localStorage.removeItem('myToken');
    }
  }

  /**
   * Sends JSON to API for logging in
   * 
   */
  handleLoginClick = () => {
    let myJSON = { "email": this.state.email, "password": this.state.password }
    const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/login"
    this.postData(url, myJSON, this.loginCallback);

  }

  /**
   * Logs out user by removing token
   * 
   */
  handleLogoutClick = () => {
    this.setState({ "authenticated": false })
    localStorage.removeItem("myToken");
  }

  /**
   * Stores password in state
   * 
   */
  handlePassword = (e) => {
    this.setState({ password: e.target.value })
  }

  /**
   * Stores email in state
   * 
   */
  handleEmail = (e) => {
    this.setState({ email: e.target.value })
  }

  /**
   * Sends JSON to API for logging in
   * 
   * @param sessionId - ID of session to be modified
   * @param name - new name of session
   * 
   */
  handleUpdateClick = (sessionId, name) => {
    const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/update"

    if (localStorage.getItem('myToken')) {
      let myToken = localStorage.getItem('myToken')
      let myJSON = {
        "token": myToken,
        "sessionId": sessionId,
        "name": name
      }
      this.postData(url, myJSON, this.updateCallback)
    } else {
      this.setState({ "authenticated": false })
    }
  }

  render() {

    let page = <Login handleLoginClick={this.handleLoginClick} email={this.state.email} password={this.state.password} handleEmail={this.handleEmail} handlePassword={this.handlePassword} />
    if (this.state.authenticated) {
      page =
        <div>
          <button onClick={this.handleLogoutClick}>Log out</button>
          <Update handleUpdateClick={this.handleUpdateClick} />
        </div>
    }

    return (
      <div>
        <h1>Admin</h1>
        {page}
      </div>
    );
  }
}

export default Admin;