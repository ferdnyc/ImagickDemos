    import { h, Component } from 'preact';


export interface IntegerProps {
    name: string;
    min: number;
    max: number;
    value: number|string;
    updateFn?(newValue:any): void;
}

export class Integer extends Component<IntegerProps, {}> {
    render() {
        // <input type="text" pattern="\d*" />
        // <input type="number" value={this.state.value} onChange={this.onChange}/>
        return <span>
            {this.props.name}
            <input
                type="number"
                name={this.props.name}
                value={this.props.value}
                // @ts-ignore: blah blah
                onchange={(event) => this.props.updateFn(event.target.value)}
            />
        </span>;
    }
}