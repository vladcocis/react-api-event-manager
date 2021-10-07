import React from 'react'

/**
* A text input for searching using a string 
*
* @author Vlad Cocis
*/
class Search extends React.Component {
  render() {
    return (
      <div>
        <input
          type='text'
          placeholder='Search'
          value={this.props.query}
          onChange={this.props.handleSearch}
        />
      </div>
    )
  }
}

export default Search;