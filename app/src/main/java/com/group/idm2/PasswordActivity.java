package com.group.idm2;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;

public class PasswordActivity extends AbstractActivity {

    private EditText passwordET, newPasswordET, confirmPasswordET;
    private SharedPreferences sharedPreferences;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_password);

        passwordET = (EditText)findViewById(R.id.passwordET);
        confirmPasswordET = (EditText)findViewById(R.id.confirmPasswordET);
        newPasswordET = (EditText)findViewById(R.id.newPasswordET);
        sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);
    }

    public void update(View view) {
        String old_password = passwordET.getText().toString().trim();
        String confirm_password = confirmPasswordET.getText().toString().trim();
        String new_password = newPasswordET.getText().toString().trim();

        String email = (sharedPreferences.getString("email", ""));
        new LoginTask(this, "passwordchange", email, old_password, confirm_password, "", "", "", new_password).execute();
    }
}
