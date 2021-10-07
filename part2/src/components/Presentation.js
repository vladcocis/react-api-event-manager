import React from 'react';

/**
 * Displays details about a presentation
 * 
 * @author Vlad Cocis
 * 
 */
class Presentation extends React.Component {

 state = {display:false, data:[]}
 render() {
   return (
     <div>
       <h4>Title: {this.props.details.title}</h4>
       <br></br>
       <div>Abstract: {this.props.details.abstract}</div>
       <br></br>
       <div>{this.props.details.award}</div>
       <br></br>
     </div>
     
   );
 }
}

export default Presentation;
