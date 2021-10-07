import React from 'react';

/**
 * Displays the title of a specific presentation
 * 
 * @author Vlad Cocis
 * 
 */
class PresentationTitles extends React.Component {

 state = {display:false, data:[]}
 render() {
   return (
     <div>
       {this.props.details.title}
     </div>
    
     
   );
 }
}

export default PresentationTitles;