import React, { Component } from 'react';

const bookContext = React.createContext();

class BookProvider extends Component {

	state = {
		loading: false,
		bookList: [],
		bookFilter: []
	};

	componentDidMount() {
		fetch('http://localhost:2000/api/books')
		.then(response => {
			return response.json();
		})
		.then(responseJson => {
			this.setState({
				bookList: responseJson
			});
			return responseJson;
		})
	}

	filterBook = (filterType, filterValue) => {
		this.setState({
				loading: true
			});
		fetch('http://localhost:2000/api/books/filter?filterType=' 
			+ filterType + '&filterValue=' + filterValue)
		.then(response => {
			return response.json();
		})
		.then(responseJson => {
			this.setState({
				bookFilter: responseJson,
				loading: false
			});
			return responseJson;
		})
	}

	render() {
		return (
			<bookContext.Provider
				value = {{
					...this.state,
					filterBook: this.filterBook
				}}
			>
				{ this.props.children }
			</bookContext.Provider>
		)
	}
}

const BookConsumer = bookContext.Consumer;

export {BookProvider, BookConsumer};