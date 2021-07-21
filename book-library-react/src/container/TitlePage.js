import React, { Component } from "react";
import BookInformation from '../components/BookInformation';
import { BookConsumer } from '../context';
import { withRouter } from 'react-router-dom';

export default class TitlePage extends Component {


	render() {

		return (
			<div>Détail du livre :
				{ this.props.location ? <div>{ this.book(this.props.location.state.book) }</div> : <div/> }
			</div>

		)
	}

	book(book) {
		return (
		  <div>

		  		<BookInformation
		  			book = {book}

				/>
		  </div>
	);
	}
}