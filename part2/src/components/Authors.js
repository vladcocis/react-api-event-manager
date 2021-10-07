import React from 'react';
import Author from './Author.js';
import Search from './Search.js';

/**
 * Displays a list of all authors, searchable by name
 * 
 * @author Vlad Cocis
 * 
 */
class Authors extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      page: 1,
      pageSize: 9,
      query: "",
      data: []
    }
  }

  /**
   * Gets JSON from API and decodes it
   * 
   */
  componentDidMount() {
    const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/authors"
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

  /**
   * Loads previous results
   */
  handlePreviousClick = () => {
    this.setState({ page: this.state.page - 1 })
  }

  /**
   * Loads next results
   */
  handleNextClick = () => {
    this.setState({ page: this.state.page + 1 })
  }

  /**
   * Filters results by author name
   */
  handleSearch = (e) => {
    this.setState({ page: 1, query: e.target.value })
  }

  /**
   * Sanitises author name string
   */
  searchString = (s) => {
    return s.toLowerCase().includes(this.state.query.toLowerCase())
  }

  /**
   * Searches results by author name
   */
  searchDetails = (details) => {
    return (this.searchString(details.name) )
  }

  render() {

    let filteredData = (
      this.state.data
        .filter(this.searchDetails)
    )

    let noOfPages = Math.ceil(filteredData.length / this.state.pageSize)
    if (noOfPages === 0) { noOfPages = 1 }
    let disabledPrevious = (this.state.page <= 1)
    let disabledNext = (this.state.page >= noOfPages)

    return (
      <div>
        <h1>Authors</h1>
        <p>Click author name to see their presentations</p>
        <Search query={this.state.query} handleSearch={this.handleSearch} />
        {
          filteredData
            .slice(((this.state.pageSize * this.state.page) - this.state.pageSize), (this.state.pageSize * this.state.page))
            .map((details, i) => (<Author key={i} details={details} />))
        }
        <button onClick={this.handlePreviousClick} disabled={disabledPrevious}>Previous</button>
       Page {this.state.page} of {noOfPages}
        <button onClick={this.handleNextClick} disabled={disabledNext}>Next</button>
      </div>
    );
  }
}

export default Authors;