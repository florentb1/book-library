import React, { Component } from "react";
import { withRouter } from 'react-router-dom';
import { BookConsumer } from '../context';
import BookInformation from '../components/BookInformation';

class BookListPage extends Component {

	

	goToTitle (navigate, book, value) {
			navigate({
				pathname: '/titre', 
				state: {
					book: book
				}
			});
		}

	goToAuthor (navigate, book, value) {
			navigate({
				pathname: '/auteur'
			});
			value.filterBook('author', book._source.author);
		}

		goToReleaseYear (navigate, book, value) {
			navigate({
				pathname: '/annee'
			});
			value.filterBook('releaseYear', book._source.releaseYear);
		}

		goToType (navigate, book, value) {
			navigate({
				pathname: '/genre'
			});
			value.filterBook('type', book._source.type);
		}

	render() {

		const navigate = this.props.history.push;

		return (
			<div>
				Ma bibliothèque :
				
				<BookConsumer>
					{(value) => {

						if (!value || 
							!value.bookList || 
							value.bookList.length === 0) {
							return (
								<div>Chargement en cours</div>
							)
						} else {
								return (
								  <div>
								  { value.bookList.map((book, i) => (
								  		<BookInformation
								  			i = {i}
								  			book = {book}
								  			goToTitle = { () => this.goToTitle(navigate, book, value) }

								  			goToAuthor = { () => this.goToAuthor(navigate, book, value) }
											
											goToReleaseYear = { () => this.goToReleaseYear(navigate, book, value) }

											goToType = { () => this.goToType(navigate, book, value) }
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

export default withRouter (BookListPage);