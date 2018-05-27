package copy.filer;

import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.AnchorPane;
import javafx.stage.DirectoryChooser;
import javafx.stage.Stage;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.nio.MappedByteBuffer;
import java.nio.channels.FileChannel;
import java.nio.file.*;
import java.nio.file.attribute.FileTime;
import java.util.ArrayList;
import java.util.stream.Stream;
import java.util.zip.CRC32;

public class Controller {

    @FXML
    private AnchorPane pane;

    @FXML
    private TextField dir1;

    @FXML
    private TextField dir2;

    @FXML
    private Button dir1Button;

    @FXML
    private Button dir2Button;

    @FXML
    private TreeView<MyPath> tree1;

    @FXML
    private TreeView<MyPath> tree2;

    @FXML
    private CheckBox processEmbedded;

    @FXML
    private Button okButton;

    @FXML
    private CheckBox showSame;

    @FXML
    private Button switchDirectoriesButton;

    @FXML
    private Button copyPathButton;

    @FXML
    private CheckBox compareSame;

    @FXML
    private TextField chosenFile;

    @FXML
    private Button copyButton;

    @FXML
    private Button copyNew;

    @FXML
    private Button copyAll;

    @FXML
    private ImageView iv1;

    @FXML
    private ImageView iv2;

    @FXML
    private ImageView iv3;

    @FXML
    private ImageView iv4;

    @FXML
    private ImageView iv5;

    @FXML
    private ImageView iv6;

    private ArrayList<Path> newFiles;

    @FXML
    void initialize() {
        newFiles = new ArrayList<>();
        iv1.setImage(new Image("/copy/filer/assets/warning.png"));
        iv2.setImage(new Image("/copy/filer/assets/size.png"));
        iv3.setImage(new Image("/copy/filer/assets/schedule.png"));
        iv4.setImage(new Image("/copy/filer/assets/file_compare.png"));
        iv5.setImage(new Image("/copy/filer/assets/file.png"));
        iv6.setImage(new Image("/copy/filer/assets/folder.png"));
        okButton.setOnAction(event -> process());
        dir1Button.setOnAction(event -> setShowDirectoryDialogAction(dir1, tree1, dir2));
        dir1.setOnKeyPressed(event -> { if(event.getCode() == KeyCode.ENTER) { buildTree(Paths.get(dir1.getText()), makeRoot(dir1, tree1)); } });
        dir2Button.setOnAction(event -> setShowDirectoryDialogAction(dir2, tree2, dir1));
        dir2.setOnKeyPressed(event -> { if(event.getCode() == KeyCode.ENTER) { buildTree(Paths.get(dir2.getText()), makeRoot(dir2, tree2)); } });
        tree1.getSelectionModel().selectedItemProperty().addListener((v, oldValue, newValue) -> {
            try {
                chosenFile.setText(newValue.getValue().path.toString());
            } catch (NullPointerException e) {}
        });
        copyButton.setOnAction(e -> {
            copy(chosenFile.getText(), dir2.getText());
            buildTree(Paths.get(dir2.getText()), makeRoot(dir2, tree2));
            buildTree1(Paths.get(dir1.getText()), makeRoot(dir1, tree1), Paths.get(dir2.getText()));
        });
        copyNew.setOnAction(e -> {
            System.out.println(newFiles.size());
            newFiles.forEach(i -> {
                System.out.println(i);
                copy(i.toString(), dir2.getText());
            });
            buildTree(Paths.get(dir2.getText()), makeRoot(dir2, tree2));
            buildTree1(Paths.get(dir1.getText()), makeRoot(dir1, tree1), Paths.get(dir2.getText()));
        });
        copyAll.setOnAction(e -> {
            String to = dir2.getText();
            try (Stream<Path> paths = Files.walk(Paths.get(dir1.getText()))) {
                paths.forEach(p -> {
                    try {
                        Path path = Paths.get(to + p.toString().substring(dir1.getText().length(), p.toString().length()));
                        if (Files.notExists(path) || Files.isRegularFile(path))
                            Files.copy(p, path, StandardCopyOption.REPLACE_EXISTING, StandardCopyOption.COPY_ATTRIBUTES);
                    } catch (IOException e1) {
                        e1.printStackTrace();
                    }
                });
            } catch (IOException e1) {
                e1.printStackTrace();
            }
            buildTree(Paths.get(dir2.getText()), makeRoot(dir2, tree2));
            buildTree1(Paths.get(dir1.getText()), makeRoot(dir1, tree1), Paths.get(dir2.getText()));
        });
        copyPathButton.setOnAction(e -> {
            dir2.setText(Paths.get(dir1.getText()).getParent().toString());
        });
        switchDirectoriesButton.setOnAction(e -> {
            String text1 = dir1.getText();
            buildTree(Paths.get(text1), makeRoot(dir1, tree2));
            buildTree1(Paths.get(dir2.getText()), makeRoot(dir2, tree1), Paths.get(text1));
            dir1.setText(dir2.getText());
            dir2.setText(text1);
        });
    }

    private void copy(String from, String to) {
        if (Files.isRegularFile(Paths.get(from))) {
            try {
                Path path = Paths.get(to  + from.substring(dir1.getText().length(), from.length()));
                if (Files.notExists(path) || Files.isRegularFile(path))
                    Files.copy(Paths.get(from), path, StandardCopyOption.REPLACE_EXISTING, StandardCopyOption.COPY_ATTRIBUTES);
            } catch (IOException e) {
                e.printStackTrace();
            }
        } else {
            try (Stream<Path> paths = Files.walk(Paths.get(from))) {
                paths
                        .forEach(f -> {
                            try {
                                Path path = Paths.get(to + from.substring(dir1.getText().length(), from.length()) + f.toString().substring(from.length(), f.toString().length()));
                                if (Files.notExists(path) || Files.isRegularFile(path))
                                    Files.copy(f, path, StandardCopyOption.REPLACE_EXISTING, StandardCopyOption.COPY_ATTRIBUTES);
                            } catch (IOException e) {
                                e.printStackTrace();
                            }
                        });
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

    private void setShowDirectoryDialogAction(TextField field, TreeView<MyPath> tree, TextField field2) {
        DirectoryChooser directoryChooser = new DirectoryChooser();
        Stage stage = (Stage) pane.getScene().getWindow();
        String initialPath = field2.getText();
        if (initialPath.length() > 0) {
            directoryChooser.setInitialDirectory(new File(Paths.get(initialPath).getParent().toString()));
        }
        File selectedDirectory =
                directoryChooser.showDialog(stage);
        if (selectedDirectory != null) {
            field.setText(selectedDirectory.getAbsolutePath());
            TreeItem<MyPath> root = makeRoot(field, tree);
            buildTree(Paths.get(field.getText()), root);
        }
    }

    private void process() {
        buildTree1(Paths.get(dir1.getText()), makeRoot(dir1, tree1), Paths.get(dir2.getText()));
    }

    private TreeItem<MyPath> makeRoot(TextField field, TreeView<MyPath> tree) {
        MyPath myPath = new MyPath(Paths.get(field.getText()));
        TreeItem<MyPath> rootItem = new TreeItem<MyPath>(myPath);
        rootItem.setExpanded(true);
        tree.setRoot(rootItem);
        return rootItem;
    }

    private void buildTree1(Path path, TreeItem<MyPath> parent, Path path2) {
        if (path.toString().equals(dir1.getText()))
            newFiles.clear();
        try (Stream<Path> paths = Files.walk(path, 1)) {
            paths
                    .forEach(f -> {
                        if (f != path) {
                            String name = f.getFileName().normalize().toString();
                            TreeItem<MyPath> item = new TreeItem<MyPath>(new MyPath(f));
                            Path p2 = path2.resolve(name);
                            boolean isSame = false;
                            if (Files.notExists(p2)) {
                                item.setGraphic(new ImageView(new Image("/copy/filer/assets/warning.png")));
                                System.out.println(newFiles.size());
                                if (path.toString().equals(dir1.getText()))
                                    newFiles.add(f);
                            } else {
                                try {
                                    String icon = "file";
                                    isSame = true;
                                    if (!Files.getAttribute(f, "size").equals(Files.getAttribute(p2, "size"))) {
                                        icon = "size";
                                        isSame = false;
                                    } else if (Files.isRegularFile(f) && Files.getLastModifiedTime(f).compareTo(Files.getLastModifiedTime(p2)) != 0) {
                                        icon = "schedule";
                                        isSame = false;
                                    } else if (compareSame.isSelected() && Files.isRegularFile(f) && checksumMappedFile(f.toString()) != checksumMappedFile(p2.toString())) {
                                        icon = "file_compare";
                                        isSame = false;
                                    } else if (Files.isDirectory(f)) {
                                        icon = "folder";
                                    }
                                    if (path.toString().equals(dir1.getText()) && Files.getLastModifiedTime(f).compareTo(Files.getLastModifiedTime(p2)) > 0) {
                                        newFiles.add(f);
                                    }
                                    item.setGraphic(new ImageView(new Image("/copy/filer/assets/"+icon+".png")));
                                } catch (IOException e) {
                                    e.printStackTrace();
                                }
                            }
                            if (!(showSame.isSelected() && isSame)) {
                                if (processEmbedded.isSelected() && Files.isDirectory(f)) {
                                    buildTree1(f, item, path2.resolve(name));
                                }
                                parent.getChildren().add(item);
                            }
                        }
                    });
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void buildTree(Path path, TreeItem<MyPath> parent) {
        try (Stream<Path> paths = Files.walk(path, 1)) {
            paths
                    .forEach(f -> {
                        TreeItem<MyPath> item = new TreeItem<MyPath>(new MyPath(f));
                        if (f != path) {
                            parent.getChildren().add(item);
                            if (Files.isDirectory(f)) {
                                buildTree(f, item);
                                item.setGraphic(new ImageView(new Image("/copy/filer/assets/folder.png")));
                            } else {
                                item.setGraphic(new ImageView(new Image("/copy/filer/assets/file.png")));
                            }
                        }
                    });
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static long checksumMappedFile(String filepath) throws IOException {
        FileInputStream inputStream = new FileInputStream(filepath);
        FileChannel fileChannel = inputStream.getChannel();
        int len = (int) fileChannel.size();
        MappedByteBuffer buffer = fileChannel.map(FileChannel.MapMode.READ_ONLY, 0, len);
        CRC32 crc = new CRC32();
        for (int cnt = 0; cnt < len; cnt++) {
            int i = buffer.get(cnt);
            crc.update(i);
        }
        return crc.getValue();
    }


    private static class MyPath {
        Path path;
        String name;

        MyPath(Path path) {
            this.path = path;
            this.name = path.getFileName().normalize().toString();
        }

        @Override
        public String toString() {
            return this.name;
        }
    }

}


