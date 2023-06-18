import './login.css'

export default function Login() {
    return (
        <body>
            <div className='main'>
                <h1>Welcome to the Login page</h1>

                <form action='/app/Home' method='post'>
                    <p>Username</p>
                    <input type='text'></input>
                    <br></br>
                    <p>Password</p>
                    <input type='password'></input>
                    <br></br>
                    <br></br>
                    <input type='submit' value='submit'></input>
                </form>
            </div>
        </body>
    );
}