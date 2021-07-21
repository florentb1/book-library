import logo from "./logo.svg";
import "./App.css";
import { Route, Switch, withRouter } from "react-router-dom";
import TitlePage from "./container/TitlePage";
import AuthorPage from "./container/AuthorPage";
import BookTypePage from "./container/BookTypePage";
import ReleaseYearPage from "./container/ReleaseYearPage";
import BookListPage from "./container/BookListPage";
import { BookProvider } from './context';

function App() {
  return (
    <BookProvider>
    <div className="App">
      <header className="App-header">
      Bibliothèque
      </header>

        <Switch>
          <Route exact path="/" component={BookListPage} />
          <Route path="/titre" component={TitlePage} />
          <Route path="/auteur" component={AuthorPage} />
          <Route path="/annee" component={BookTypePage} />
          <Route path="/genre" component={ReleaseYearPage} />
        </Switch>
    </div>
    </BookProvider>
  );
}

export default withRouter(App);
