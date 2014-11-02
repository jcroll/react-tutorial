var CommentBox = React.createClass({
    loadCommentsFromServer: function() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getInitialState: function() {
        return {data: []};
    },
    componentDidMount: function() {
        this.loadCommentsFromServer();
        setInterval(this.loadCommentsFromServer, this.props.pollInterval);
    },
    render: function() {
        return (
            <div className="panel panel-default">
            <div className="panel-heading">
                <h1 className="panel-title">Comments</h1>
            </div>
            <div className="panel-body">
                <CommentList data={this.state.data} />
                <CommentForm />
            </div>
        </div>
        );
    }
});
var CommentList = React.createClass({
    render: function() {
        var commentNodes = this.props.data.map(function (comment) {
            return (
                <Comment author={comment.author}>
                    {comment.text}
                </Comment>
            );
        });
        return (
            <div className="commentList">
                {commentNodes}
            </div>
        );
    }
});
var converter = new Showdown.converter();
var Comment = React.createClass({
    render: function() {
        var rawMarkup = converter.makeHtml(this.props.children.toString());
        return (
            <div className="panel panel-default">
            <div className="panel-heading">{this.props.author}</div>
                <div className="panel-body">
                    <span dangerouslySetInnerHTML={{__html: rawMarkup}} />
                </div>
            </div>
        );
    }
});
var CommentForm = React.createClass({
    render: function() {
        return (
            <div className="commentForm">
            Hello, world! I am a CommentForm.
            </div>
        );
    }
});
React.render(
    <CommentBox url={commentsUrl} pollInterval={2000} />,
    document.getElementById('content')
);