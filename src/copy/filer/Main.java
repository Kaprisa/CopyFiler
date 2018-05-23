package copy.filer;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class Main extends Application {

    @Override
    public void start(Stage primaryStage) throws Exception{
        Parent root = FXMLLoader.load(getClass().getResource("copy_filer.fxml"));
        primaryStage.setTitle("CopyFiler");
        primaryStage.setScene(new Scene(root, 1168, 715));
        primaryStage.show();
    }


    public static void main(String[] args) {
        launch(args);
    }
}
