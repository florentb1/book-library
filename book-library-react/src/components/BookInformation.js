import React, { Component } from "react";

export default class BookInformation extends Component 
{

	render() {
		const { book, goToTitle, i, goToAuthor, goToReleaseYear, goToType } =  this.props;

		return (
			<div>
				<ul key={i}>

					<li><div onClick = { goToTitle } > 
						{ book._source.title } </div>
					</li>

					<li><div onClick = { goToAuthor } > 
						{ book._source.author } </div>
					</li>

					<li><div onClick = { goToReleaseYear } > 
						{ book._source.releaseYear } </div>
					</li>

					<li><div onClick = { goToType } > 
						{ book._source.type } </div>
					</li>
				</ul>
			</div>
		)
	}
}