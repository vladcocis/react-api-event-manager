import React from 'react';
import { BrowserRouter as Router, Switch, Route, NavLink } from "react-router-dom";
import Authors from './components/Authors';
import NotFound404 from './components/NotFound404'
import Schedule from './components/Schedule.js';
import Admin from './components/Admin.js';
import './App.css';

/**
 * Creates the React App using ReactRouter
 * 
 * @author Vlad Cocis
 * 
 */
function App() {
  return (
    <Router basename="/Year3/Web/Assignment/part2">
      <div className="App">
        <nav>
          <ul>
            <li>
              <NavLink activeClassName="selected" exact to="/">Schedule</NavLink>
            </li>
            <li>
              <NavLink activeClassName="selected" to="/authors">Authors</NavLink>
            </li>
            <li>
              <NavLink activeClassName="selected" to="/admin">Admin</NavLink>
            </li>
          </ul>
        </nav>
        <Switch>
          <Route path="/authors">
            <Authors />
          </Route>
          <Route path="/admin">
            <Admin />
          </Route>
          <Route exact path="/">
            <Schedule />
          </Route>
          <Route path="*">
            <NotFound404 />
          </Route>
        </Switch>
      </div>
    </Router>
  );
}

export default App;