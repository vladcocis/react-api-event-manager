import React from 'react';
import PresentationTitles from './PresentationTitles.js';

/**
 * Displays author publication details
 * 
 * @author Vlad Cocis
 * 
 */
class Author extends React.Component {

 state = {display:false, data:[]}

 /**
 * Decodes JSON of presentations by a specific author from API
 */
 loadPresentationDetails = () => {
  const url = "http://unn-w18017881.newnumyspace.co.uk/Year3/Web/Assignment/part1/api/presentations?id=" + this.props.details.authorId
   fetch(url)
     .then( (response) => response.json() )
     .then( (data) => {
       this.setState({data:data.data})
     })
      .catch ((err) => {
        console.log("something went wrong ", err)
     }
   );
 }

 /**
 * Displays presentations on click
 * 
 */
 handleAuthorClick = (e) => {
   this.setState({display:!this.state.display})
   this.loadPresentationDetails()
 }

 render() {

   let presentations = ""
   if (this.state.display) {
     presentations =this.state.data.map( (details, i) => (<PresentationTitles key = {i} details={details} />) )
   }

   return (
     <div>
       <h2 onClick={this.handleAuthorClick}>{this.props.details.name}</h2>
       {presentations}
     </div>
   );
 }
}

export default Author;
