import './register.css'

export default function Register() {
    return (
        <body>
            <div className='main'>
                <h1>Welcome to the Register page</h1>

                <form>
                    <p>Username</p>
                    <input type='text'></input>
                    <br></br>
                    <p>Password</p>
                    <input type='password'></input>
                    <br></br>
                    <br></br>
                    <input type='button' value='Register'></input>
                </form>
            </div>
        </body>
    );
}