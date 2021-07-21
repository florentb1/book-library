import React, { Component } from "react";
import { BookConsumer } from '../context';
import BookInformation from '../components/BookInformation';

export default class ReleaseYearPage extends Component {

	render() {

		const navigate = this.props.history.push;

		return (
			<div>
			<BookConsumer>
					{(value) => {

						if (!value || 
							!value.bookFilter || 
							value.bookFilter.length === 0 ||
							value.loading === true) {
							return (
								<div>Chargement en cours</div>
							)
						} else {
								return (
								  <div>
								  { value.bookFilter.map((book, i) => (
								  		<BookInformation
								  			i = {i}
								  			book = {book}
						
										/>
										))}
								  </div>
							);
						}
						
					}}
				</BookConsumer>
			</div>
		)
	}
}